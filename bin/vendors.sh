#!/bin/sh

DIR=`php -r "echo realpath(dirname(\\$_SERVER['argv'][0]));"`
VENDOR=$DIR/vendor_full

# initialization
if [ "$1" = "--reinstall" ]; then
    rm -rf $VENDOR
fi

mkdir -p $VENDOR && cd $VENDOR

##
# @param destination directory (e.g. "doctrine")
# @param URL of the git remote (e.g. git://github.com/doctrine/doctrine2.git)
# @param revision to point the head (e.g. origin/HEAD)
#
install_git()
{
    INSTALL_DIR=$1
    SOURCE_URL=$2
    REV=$3

    if [ -z $REV ]; then
        REV=origin/HEAD
    fi

    if [ ! -d $INSTALL_DIR ]; then
        git clone $SOURCE_URL $INSTALL_DIR
    fi

    cd $INSTALL_DIR
    git fetch origin
    git reset --hard $REV
    cd ..
}

# Assetic
install_git assetic git://github.com/kriswallsmith/assetic.git origin/master

# Doctrine ORM
install_git doctrine git://github.com/doctrine/doctrine2.git 2.0.1

# Doctrine Data Fixtures Extension
install_git doctrine-data-fixtures git://github.com/doctrine/data-fixtures.git origin/master

# Doctrine DBAL
install_git doctrine-dbal git://github.com/doctrine/dbal.git 2.0.1

# Doctrine Common
install_git doctrine-common git://github.com/doctrine/common.git 2.0.1

# Doctrine migrations
install_git doctrine-migrations git://github.com/doctrine/migrations.git origin/master

# Doctrine MongoDB
install_git doctrine-mongodb git://github.com/doctrine/mongodb.git origin/master

# Doctrine MongoDB
install_git doctrine-mongodb-odm git://github.com/doctrine/mongodb-odm.git origin/master

# Swiftmailer
install_git swiftmailer git://github.com/swiftmailer/swiftmailer.git origin/4.1

# Twig
install_git twig git://github.com/fabpot/Twig.git origin/master

# Zend Framework
install_git zend git://github.com/zendframework/zf2.git origin/master
cd zend
git submodule update --recursive --init
cd ..
