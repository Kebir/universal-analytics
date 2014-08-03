all: test

start-server:
	@ps aux | grep 'node tests/server.js' | grep -v grep \
	|| node tests/server.js &> /dev/null

stop-server:
	PID=`ps axo pid,command | grep 'tests/server.js' | grep -v grep | sed 's/^ *//' | cut -f 1 -d " "` && \
	kill $$PID && \
	true

test: start-server
	sleep 0.2s
	vendor/bin/phpunit
	$(MAKE) stop-server > /dev/null
