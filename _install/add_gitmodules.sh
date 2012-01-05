#! /bin/bash
#
# quick fix for my issue here, same as:
#   http://stackoverflow.com/questions/3336995/git-will-not-init-sync-update-new-submodules
#

pushd ..

# [submodule "lib/includes/js/mootools-core"]
git submodule add git@github.com:GerHobbelt/mootools-core.git  lib/includes/js/mootools-core
# [submodule "lib/includes/js/mootools-more"]
git submodule add git@github.com:GerHobbelt/mootools-more.git  lib/includes/js/mootools-more
# [submodule "lib/includes/js/lazyload"]
git submodule add git@github.com:GerHobbelt/lazyload.git  lib/includes/js/lazyload
# [submodule "lib/includes/js/mochaui"]
git submodule add git@github.com:GerHobbelt/mochaui.git  lib/includes/js/mochaui
# [submodule "lib/includes/js/tinymce"]
git submodule add git@github.com:GerHobbelt/tinymce.git  lib/includes/js/tinymce
# [submodule "lib/includes/rgrove-jsmin"]
git submodule add git@github.com:GerHobbelt/jsmin-php.git  lib/includes/rgrove-jsmin

popd

