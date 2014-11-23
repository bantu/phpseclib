#!/bin/sh
#
# This file is part of the phpseclib project.
#
# (c) Andreas Fischer <bantu@phpbb.com>
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#

HOSTNAME='phpseclib.bantux.org'
LDIRNAME='code_coverage'
RDIRNAME='code_coverage'

HERE=$(dirname "$0")
SSH_CONFIG="$HERE/code_coverage_ssh_config"
SSH_ID_RSA="$HERE/code_coverage_id_rsa"
SSH_KHOSTS="$HERE/code_coverage_known_hosts"

# Workaround for rsync not creating target directories with depth > 1
mv "$LDIRNAME" "x$LDIRNAME"
RROOT="$RDIRNAME/$TRAVIS_BRANCH/$TRAVIS_BUILD_NUMBER"
mkdir -p "$RROOT"
mv "x$LDIRNAME" "$RROOT/PHP-$TRAVIS_PHP_VERSION/"

# Update latest symlink
ln -s "$TRAVIS_BUILD_NUMBER" "$RDIRNAME/$TRAVIS_BRANCH/latest"

# Stop complaints about world-readable key file.
chmod 600 "$SSH_ID_RSA"

rsync \
  --rsh="ssh -F $SSH_CONFIG -i $SSH_ID_RSA -o UserKnownHostsFile=$SSH_KHOSTS" \
  --archive \
  "$RDIRNAME/" "$USERNAME@$HOSTNAME:$RDIRNAME/"
