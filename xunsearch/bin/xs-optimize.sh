#!/bin/sh
# try to optimize the app database
# $Id$

prefix=/www/server/xunsearch

# project
if test $# -lt 1 ; then
  echo "Usage: $0 <project>"
  exit 1
else
  project=$1
fi

# home
cd $prefix
home=$prefix/data/$1
if ! test -d $home ; then
  echo "ERROR: project dose not exists: $project"
  exit 2
fi

# stop index server
bin/xs-ctl.sh -s index stop

# optmize db list
dbs="db log_db"
for d in $dbs
do
  if test -d $home/$d; then
    echo -n "Optimizing $1/$d ..."
    bin/xapian-compact $home/$d $home/${d}_tmp
    rm -rf $home/${d}_old
    mv -f $home/$d $home/${d}_old
    mv -f $home/${d}_tmp $home/$d
    echo " OK"
  fi
done

# start index server
bin/xs-ctl.sh -s index start
