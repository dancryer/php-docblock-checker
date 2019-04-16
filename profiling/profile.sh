#!/usr/bin/env bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"

docker run --rm -e REPORT=profiling/report -v ${DIR}/../:/code phperf/php-profiler php ./bin/phpdoccheck

${DIR}/../vendor/bin/xh-tool top ${DIR}/report --limit 5 --strip-nesting