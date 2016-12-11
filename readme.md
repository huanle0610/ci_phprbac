# ci_phprbac

codeigniter use [phprbac](https://github.com/OWASP/rbac) 

# features

- use database library of codeigniter instead of phprbac database library
- visualization of relationships, thanks plantuml
![click one role,highlight its permissions](http://ww3.sinaimg.cn/large/62dabf66gw1famorhph2dj217h0jtdkp.jpg)

# step

- nginx config

```nginx
server {
    listen 80;
    server_name www.cirbac.cc;
    root   E:/git/ci_phprbac/;
    
    index  index.php index.html index.htm;

    location ~* \.(ico|css|js|gif|jpe?g|png)(\?[0-9]+)?$ {
            #expires max;
            log_not_found off;
    }

    location / {
        try_files $uri $uri/ /index.php; 
    } 
    
    location ~* \.php$ { 
        fastcgi_pass 127.0.0.1:9001; 
        include fastcgi.conf; 
        fastcgi_index  index.php;
        fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```


- import [sql](extra/phprbac.sql)

- throw [plantuml](extra/plantuml.war) in [tomcat](http://tomcat.apache.org/download-70.cgi)
