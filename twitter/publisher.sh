#!/bin/sh

# Preleva i dati raw sulle registrazioni, li compara con l'ultima copia salvata
# localmente, e per ogni nuova citta' spedisce un aggiornamento sull'account
# Twitter per mezzo di twidge ( https://github.com/jgoerzen/twidge )
# Il file di configurazione OAuth per twidge non viene divulgato per ovvie
# ragioni di sicurezza

wget http://www.linuxday.it/data
lines=`wc -l data | cut -d' ' -f 1`

tail -n $(($lines - 1)) data > data.tmp
mv data.tmp data

if [ ! -e olddata ]
then
	mv data olddata
	exit
fi

while read i
do
	i=`echo $i | sed "s/\",\"/#/g"`
	city=`echo $i | cut -d'#' -f 4`

	if [ `grep "$city" olddata | wc -l` -eq 0 ]
	then
		link=`echo $i | cut -d'#' -f 6 | sed "s/..$//g"`

		if [ "$link" != "" ]
		then
			link=`python ur1.py $link`

			if [ $? -eq 0 ]
			then
				# twidge -c /home/madbob/.twidgerc-linuxday-twitter update "registrato un nuovo LinuxDay a $city: $link"
				echo "registrato un nuovo LinuxDay a $city: $link"
			fi
		fi
	fi
done < "data"

mv data olddata
