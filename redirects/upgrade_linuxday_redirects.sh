#!/bin/sh

# Script che pesca il file della mappatura per i redirect e lo copia sul server di linuxday.it
# E' gestito separatamente dallo script che crea suddetto file in quanto viene eseguito su una diversa macchina
# Le credenziali WebDav sono nel file ~/.netrc che non viene reso pubblico per ovvie ragioni di sicurezza

cd /tmp
wget http://66.249.9.11/exposed/linuxday2011/redirects.txt
mv redirects.txt map.txt

rm pippo
echo "put map.txt" > pippo
cadaver http://redir.linuxday.it/dav/ < pippo
