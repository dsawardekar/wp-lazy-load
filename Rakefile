require 'ostruct'
require 'erb'

plugin_slug = "wp-lazy-load"
version     = ENV['VERSION']
destination = "tmp/dist/#{version}"

namespace :git do
  desc "Update .gitignore"
  task :ignore do
    cp 'lib/templates/gitignore', '.gitignore'
    sh 'git add .gitignore'
    sh 'git commit -m "Updates .gitignore"'
  end

  task :vendor do
    sh 'git add vendor'
    sh 'git commit -m "Adds vendor [ci skip]"'
  end

  task :clean do
    sh 'rm -rf tmp'              if File.directory?('tmp')
    sh 'rm wp-cli.local.yml'     if File.exists?('wp-cli.local.yml')

    sh 'git rm *.json'
    sh 'git rm *.lock'
    sh 'git rm -r test'
    sh 'git rm -r bin'
    sh 'git rm phpunit.xml'
    sh 'git rm Gemfile'
    sh 'git rm Rakefile'
    sh "git rm -rf js/#{plugin_slug}" if File.directory?("js/#{plugin_slug}")
    sh 'git rm .scrutinizer.yml'      if File.exists?('.scrutinizer.yml')
    sh 'git rm .coveralls.yml'        if File.exists?('.coveralls.yml')

    sh 'git commit -m "Removes development files [ci-skip]"'
  end

  task :clear_after do
    sh 'git rm -r lib/templates' if File.directory?('lib/templates')
    sh 'git commit -m "Cleaning up after dist [ci skip]"'
  end

  # todo: conditionally add js libs
  task :js do
  end

  task :archive do
    sh "rm -rf #{destination}" if File.directory?(destination)
    mkdir_p destination
    sh "git archive dist-#{version} --format tar | (cd tmp/dist/#{version} && tar xf -)"
  end

  task :dist_branch do
    sh "git checkout -b dist-#{version}"
  end

  task :dev_branch do
    sh "git checkout develop"
  end

  task :dist_publish do
    sh "git push origin dist-#{version}"
  end
end

namespace :bower do
  desc "Copy Bower libraries"
  task :copy do
    cp 'bower_components/jquery.lazyload/jquery.lazyload.js', 'js/jquery-lazy-load.js'
  end

  desc "Update Bower libraries"
  task :update do
    sh 'bower update'
  end
end

namespace :composer do
  desc "Update Composer dependencies"
  task :update do
    sh 'rm -rf vendor' if File.directory?('vendor')
    sh 'composer update --no-dev'

    # todo: use porcelain if this isn't good enough
    changed = `git status`
    if !changed.include?('working directory clean')
      sh 'git add composer.lock'
      sh 'git commit -m "Fresh composer update"'
    end
  end
end

namespace :svn do
  desc "Copy files to svn trunk"
  task :copy do
    sh "rsync -a --delete tmp/dist/#{version}/ ../svn/trunk --exclude=.gitignore --exclude=.travis.yml"
  end

  desc "Add changed files to svn"
  task :add do
    Dir.chdir('../svn') do
      sh "svn add . --force"
    end
  end

  desc "Commit changed files to svn"
  task :commit do
    Dir.chdir('../svn/trunk') do
      sh "svn commit -m 'Release #{version}'"
    end
  end

  desc "Create release branch"
  task :branch do
    Dir.chdir('../svn') do
      repo  = "http://plugins.svn.wordpress.org/#{plugin_slug}"
      trunk = "#{repo}/trunk"
      tag   = "#{repo}/branches/#{version}"

      sh "svn copy #{trunk} #{tag} -m 'Release Branch: #{version}'"
    end
  end

  desc "Create release tag"
  task :tag do
    Dir.chdir('../svn') do
      repo  = "http://plugins.svn.wordpress.org/#{plugin_slug}"
      trunk = "#{repo}/trunk"
      tag   = "#{repo}/tags/#{version}"

      sh "svn copy #{trunk} #{tag} -m 'Release Tag: #{version}'"
    end
  end
end

task :dist_check do
  fail "Version not specified" if version.nil?
end

desc 'Create a new Distribution'
task :dist => [
  'dist_check',
  'git:dist_branch',
  'composer:update',
  'git:clean',
  'git:ignore',
  'git:vendor',
  'git:clear_after',
  'git:dist_publish',
  'git:dev_branch'
]

desc 'Publish to wordpress.org'
task :publish => [
  'dist',
  'git:archive',
  'svn:copy',
  'svn:add',
  'svn:commit',
  'svn:branch',
  'svn:tag'
]

desc 'Initialize - after distribution'
task :init => [
  'composer:update',
  'bower:update'
]
