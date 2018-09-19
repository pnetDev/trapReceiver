#!/usr/bin/python
## CM 180918 This script is called by /opt/parseTrap.sh and emails the trap to 'toaddr'
"""The first step is to create an SMTP object, each object is used for connection
with one server."""

import smtplib
import sys
from email.MIMEMultipart import MIMEMultipart
from email.MIMEText import MIMEText
body = sys.argv[1]
print body

server = smtplib.SMTP('smtp.permanet.ie', 25)
#Next, log in to the server
server.login("c.maverley@permanet.ie", "xxxx")
fromaddr = "r.oleary@hsdatasolutions.com"
toaddr = "c.maverley@permanet.ie,alerts@permanet.ie"
msg = MIMEMultipart()
msg['From'] = fromaddr
msg['To'] = toaddr
msg['Subject'] = "Trap Received"
#body = "This is the body text"
msg.attach(MIMEText(body, 'plain'))
text = msg.as_string()
server.sendmail(fromaddr, toaddr, text)
print "Sent",fromaddr,toaddr,text
