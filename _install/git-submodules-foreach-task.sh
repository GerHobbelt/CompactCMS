#! /bin/bash
#
# this script is meant to run as the git submodule foreach command; when you
# use this script, any errors reported by 'git push' (e.g. when you don't 
# have push access on that particular repo) do NOT abort the 'foreach' run
# so that you can update all repo's in one single 'foreach' command...
#
# Example commandline to update all submodules and checkout their 'master' branch:
#   _install/git-submodules-foreach-task.sh foreach checkout
#

pwd
echo arguments: $0 $@

case "$1" in
checkout)
	echo checkout to master branch...
	git checkout master
	git pull --all
	;;
commit)	echo commit a.k.a. checkin
	shift
	git commit $@
	;;
fetch)	echo fetch remotes...
	git fetch --all
	git fetch --tags
	;;
push)	echo push commits...
	git push --all
	;;
foreach) echo Performing action on every submodule recursively...
	shift
	git submodule foreach --recursive $(pwd)/$0 $@
	;;
init) echo Initializing the submodules recursively...
	shift
	# so you can extra arguments like '--recursive':
	git submodule update --init $@
	;;
*)
	echo unsupported command parameter: $1
	echo supported commands: foreach, checkout, commit, fetch, push, init
	;;
esac

exit 0;


