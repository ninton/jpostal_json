FROM centos:7
MAINTAINER aoki_makoto@ninton.co.jp
RUN yum install -y http://rpms.famillecollet.com/enterprise/remi-release-7.rpm
RUN yum install -y epel-release
RUN yum -y update
RUN yum clean all
RUN yum install -y httpd
RUN echo "ServerName localhost" >> /etc/httpd/conf/httpd.conf
COPY etc/httpd/conf.d/vhosts.conf /etc/httpd/conf.d/
RUN systemctl enable httpd
CMD ["/usr/sbin/httpd", "-D", "FOREGROUND"]
RUN yum install -y wget
RUN yum install -y vim
RUN yum install -y grep
RUN yum install -y less
