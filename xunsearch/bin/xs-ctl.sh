#!/bin/sh
# xunsearch server control script
# $Id$

prefix=/www/server/xunsearch
bind=local
port=8383
server=both
opt_pub=
opt_index="-l tmp/indexd.log"
opt_search="-l tmp/searchd.log"

# usage
show_usage()
{
  echo "Usage: $0 [OPTION] COMMAND"
  echo "OPTION:"
  echo "  -b <local|unix|inet>      bind address or path"
  echo "  -L <log_level>            log level, 1-7"
  echo "  -s <index|search|both>    server type"
  echo "  -n <num>                  number of search worker process"
  echo "  -p <port>                 port number of index server"
  echo "                            port number of search is <port+1>"
  echo "COMMAND:"
  echo "  {start|stop|restart|faststop|fastrestart|reload}"
}

# options
while getopts "hb:L:s:n:p:" opt; do
  case $opt in
    b)
      bind=$OPTARG
      ;;
    L)
      opt_pub="$opt_pub -L $OPTARG"
      ;;
    s)
      server=$OPTARG
      ;;
    n)
      opt_search="$opt_search -n $OPTARG"
      ;;
    p)
      port=$OPTARG
      ;;
    h)
      show_usage
      exit 0
      ;;
    *)
      show_usage
      exit 1
    ;;
  esac
done

# command
if test $OPTIND -gt $# ; then
  echo "ERROR: No command specified" >&2
  show_usage
  exit 2
fi
shift `expr $OPTIND - 1`
cmd=$1

# reset bind
case $bind in
  local)
    opt_index="$opt_index -b 127.0.0.1:$port"
    opt_search="$opt_search -b 127.0.0.1:"`expr $port + 1`
    ;;
  unix)
    opt_index="$opt_index -b tmp/indexd.sock"
    opt_search="$opt_search -b tmp/searchd.sock"
    ;;
  inet)
    opt_index="$opt_index -b $port"
    opt_search="$opt_search -b "`expr $port + 1`
    ;;
  *)
    opt_index="$opt_index -b ${bind}:$port"
    opt_search="$opt_search -b ${bind}:"`expr $port + 1`
    ;;
esac

# home
cd $prefix

# run
case "$cmd" in
  start|stop|faststop|fastrestart|restart|reload)
  # index
  if test "$server" != "search"; then
    bin/xs-indexd $opt_index $opt_pub -k $cmd
  fi
  # search
  if test "$server" != "index"; then
    bin/xs-searchd $opt_search $opt_pub -k $cmd
  fi 
  ;;  
*)
  echo "Unknown command: $cmd"
  show_usage
  ;;  
esac

