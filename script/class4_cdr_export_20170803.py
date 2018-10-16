#!/usr/bin/env python3
import argparse
import io
from configparser import RawConfigParser
import time
import datetime
import os
import os.path
import stat
import subprocess
import base64
import uuid

import psycopg2
import psycopg2.extras


def load_config(config_file_path):
    ini_str = open(config_file_path, 'r').read()
    ini_fp  = io.StringIO(ini_str)
    config = RawConfigParser(strict=False, allow_no_value=True)
    config.readfp(ini_fp)
    return config


def parse_args():
    parser = argparse.ArgumentParser(description="CDR Export")
    parser.add_argument('-c', '--config', required=True,
                        dest="config", help="Config File")
    parser.add_argument('-i', '--log', required=True, type=int,
                        dest='log_id', help='Log ID')
    args = parser.parse_args()
    return args


def export_cdr(log_id, config):
    error_flg = False
    conn = psycopg2.connect(host=config.get('db','hostaddr'),
                                port=config.get('db','port'),
                                database=config.get('db','dbname'),
                                user=config.get('db','user'),
                                password=config.get('db','password'))
    conn.autocommit = True
    cur = conn.cursor(cursor_factory=psycopg2.extras.DictCursor)
    cur.execute("SELECT * FROM cdr_export_log WHERE id = %s", (log_id, ))
    cdr_export_log = cur.fetchone()

    cur.execute("UPDATE cdr_export_log SET status = 1 WHERE id = %s", (log_id, ))

    export_path = os.path.realpath(os.path.join(os.path.dirname(__file__), os.path.pardir, 'db_nfs_path', 'cdr_download'))
    if not os.path.exists(export_path):
        os.makedirs(export_path)
    try:
        os.chmod(export_path, stat.S_IRWXO+stat.S_IRWXU+stat.S_IRWXG)
    except:
        print ("chmod 777 failed")


    cdr_start = datetime.datetime.strptime(str(cdr_export_log['cdr_start_time'])[0:19], "%Y-%m-%d %H:%M:%S")
    cdr_end = datetime.datetime.strptime(str(cdr_export_log['cdr_end_time'])[0:19], "%Y-%m-%d %H:%M:%S")

    print ("start %s  end %s" % (cdr_start,cdr_end))
    #total_days = (cdr_end - cdr_start).days
    #cur.execute("UPDATE cdr_export_log SET total_days = %s, file_dir = %s WHERE id = %s", (total_days, export_path, log_id, ))

    cur.execute("select TABLE_NAME as name from INFORMATION_SCHEMA.TABLES where TABLE_NAME like'client_cdr2%' order by TABLE_NAME limit 1")
    table_info = cur.fetchone()
    last_time_name = table_info['name'][10:]
    last_table_time = datetime.datetime.strptime(last_time_name, "%Y%m%d")

    # print (type(last_table_time),last_table_time)
    # print (type(cdr_start),cdr_start)

    if cdr_start < last_table_time:
        cdr_start = last_table_time
    now = datetime.datetime.now()
    if cdr_end > datetime.datetime(now.year,now.month,now.day):
        cdr_end = datetime.datetime(now.year,now.month,now.day)

    print ("start %s  end %s" % (cdr_start,cdr_end))

    this_download_path_name = str(cdr_export_log['id'])+'_'+str(int(time.time()))

    log_file_path_name = os.path.join(export_path, this_download_path_name)
    if not os.path.exists(log_file_path_name):
        os.makedirs(log_file_path_name)
    try:
        os.chmod(log_file_path_name, stat.S_IRWXO+stat.S_IRWXU+stat.S_IRWXG)
    except:
        print ("chmod 777 failed")
    print (log_file_path_name)


    cur.execute("UPDATE cdr_export_log SET status = 2,finished_date = 0 WHERE id = %s", (log_id, ))
    total_row = 0
    total_days = 0
    completed_days = 0
    if cdr_start > cdr_end:
      cdr_start = cdr_end

    while cdr_start <= cdr_end:
        print( cdr_start )
        total_days = total_days + 1
        time_str = cdr_start.strftime('%Y%m%d')
        # print ("this time is %s time str %s" % (cdr_start,time_str))
        # this_where = cdr_export_log['where_sql'].replace('client_cdr.','client_cdr'+time_str+'.')
        # this_show_fields = cdr_export_log['show_fields_sql'].replace('client_cdr.','client_cdr'+time_str+'.')
        this_show_fields = cdr_export_log['show_fields_sql']
        sql = this_show_fields

        #分天导入文件
        this_file_name = os.path.join(log_file_path_name, time_str+'.csv')
        copy_sql  = "COPY (%s) TO STDOUT WITH CSV HEADER " % (sql)

        error_flg = False

        copy_sql = copy_sql.replace('client_cdr','client_cdr%s' % time_str)
        print(copy_sql)
        try:
            handle = open(this_file_name, "w")
        except:
            error_flg = True
            error_msg = 'Download file path do not have write permissions'
            # cur.execute("UPDATE cdr_export_log SET status = -1 , error_msg = 'Download file path do not have write permissions' WHERE id = %s", (log_id, ))
            break

        error_msg = ''
        # cur.execute(copy_sql)
        try:
            cur.copy_expert(copy_sql,handle)
        except (psycopg2.extensions.QueryCanceledError, psycopg2.OperationalError):
            # print(psycopg2.extensions.QueryCanceledError)
            # print(psycopg2.OperationalError)
            error_flg = True
            error_msg = psycopg2.extensions.QueryCanceledError + "\n" + psycopg2.OperationalError
        except psycopg2.DatabaseError:
            print(psycopg2.DatabaseError)
            error_flg = True
            # error_msg = psycopg2.DatabaseError
            error_msg = 'Database error'
        handle.close()
        if error_flg == True:
            break
        else:
            rows_cmd = "wc -l %s" % (this_file_name)
            rows_result = subprocess.check_output(rows_cmd, shell=True)
            rows = int(rows_result.decode().split( )[0]) - 1
            total_row += rows
            completed_days = completed_days + 1
            cur.execute("UPDATE cdr_export_log SET completed_days = %s, finished_date = finished_date + 1 WHERE id = %s", (completed_days,log_id, ))
            #compress
            # cmd = "cat %s | gzip > %s.gz" % (export_file_path, export_file_path)



        cdr_start = cdr_start + datetime.timedelta(days=1)


    # return

    if error_flg == True:
        cur.execute("UPDATE cdr_export_log SET status = -1 , error_msg = %s WHERE id = %s", (error_msg,log_id, ))
        cur.close()
        conn.close()
        return

    cur.execute("UPDATE cdr_export_log SET status = 3, file_rows = %s WHERE id = %s", (total_row,log_id, ))

    os.chdir(export_path)

    result_file_name = str(log_id)+str(uuid.uuid4())+'.zip'
    #cdr_export_log['file_name'].replace('.csv','.zip').replace('.tar.bz2', '.zip').replace('3zip','.zip')

    #cmd = "tar -jcvf %s %s" % (result_file_name,this_download_path_name)
    cmd = "zip %s %s"% (result_file_name,this_download_path_name+'/*')
    os.system(cmd)
    print (cmd)
    os.system('rm -rf %s' % this_download_path_name)
    cur.execute("UPDATE cdr_export_log SET status = 4 WHERE id = %s", (log_id, ))
    cur.execute("UPDATE cdr_export_log SET finished_date = finished_date + 1, finished_time = CURRENT_TIMESTAMP WHERE id = %s", (log_id, ))

    result_path = os.path.dirname(result_file_name)
    print (log_file_path_name)
    print( total_days)
    cur.execute("UPDATE cdr_export_log SET file_dir = %s, file_path = %s, total_days = %s, file_name = %s WHERE id = %s", (log_file_path_name,result_path, total_days, result_file_name, log_id, ))

    cur.close()
    conn.close()





def get_smtp_info(cursor):
    sql = """SELECT smtphost as host,smtpport as port,emailusername as username,emailpassword as password,loginemail as is_auth,
				fromemail as from_email, smtp_secure as smtp_secure FROM system_parameter LIMIT 1"""
    cursor.execute(sql)
    smtp_setting = cursor.fetchone()
    return smtp_setting


def get_smtp_info_by_send(cur,send_mail_id):
    sql = """SELECT  smtp_host AS host, smtp_port AS port,username,password as  password,loginemail as is_auth,
email as from_email,name as name, secure as smtp_secure FROM mail_sender where id = %s"""
    cur.execute(sql,(send_mail_id,))
    smtp_setting = cur.fetchone()
    return smtp_setting


def get_cdr_download_template(cur):
    sql = """SELECT download_cdr_from,download_cdr_subject,download_cdr_content,download_cdr_cc FROM mail_tmplate limit 1"""
    cur.execute(sql)
    return cur.fetchone()


def cdr_send_mail(cur,log_id,send_mail,web_base_url):
    template_info = get_cdr_download_template(cur)
    if template_info['download_cdr_from'] == 'Default' or template_info['download_cdr_from'] == 'default':
        smtp_setting = get_smtp_info(cur)
    else:
        smtp_setting = get_smtp_info_by_send(cur,template_info['download_cdr_from'])
        if smtp_setting is None:
            smtp_setting = get_smtp_info(cur)
    mail_info = {}
    for (d,x) in smtp_setting.items():
        mail_info[d] = x

    content = template_info['download_cdr_content']
    download_url = web_base_url+'cdrreports_db/export_log_down?key='+ base64.b64encode(str(log_id).encode()).decode()
    download_btn = "<a href='{}'>Download Link</a>".format(download_url)
    if content is not None and '{download_link}' in content:
        content = content.replace('{download_link}',download_btn)
    else:
        content += '<br />download link is :'+download_btn

    mail_info['subject'] = template_info['download_cdr_subject']
    mail_info['to'] = send_mail
    mail_info['cc'] = template_info['download_cdr_cc']
    mail_info['content'] = content
    return_info = SendMail.send_mail(mail_info)
    print (return_info)
    save_email_log(cur,return_info,mail_info)


def save_email_log(cur,return_info,mail_info):
    sql = """INSERT INTO email_log (send_time,type,email_addresses,status,error,subject,content)
values (current_timestamp(0),5,%s,%s,%s,%s,%s )"""
    if return_info['status'] == True:
        status = 0
    else:
        status = 1
    cur.execute(sql,(mail_info['to'],status,return_info['msg'],mail_info['subject'],mail_info['content']))


def main():
    args = parse_args()
    print(args.config)
    config = load_config(args.config)
    # print(config.get('Database','database'))
    export_cdr(args.log_id, config)

if __name__ == "__main__":
    main()