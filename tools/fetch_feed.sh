#/bin/sh
dir=$(dirname $(which $0));
#or http://twitter.com/statuses/user_timeline/255481440.rss
# From pipes to remove Username
#http://pipes.yahoo.com/pipes/pipe.info?_id=zncl_xDx2xGVxuG5p2IyXQ
wget "http://pipes.yahoo.com/pipes/pipe.run?_id=zncl_xDx2xGVxuG5p2IyXQ&_render=rss&user=nat_sciences" -O "$dir/../data/feed/feed.xml"
