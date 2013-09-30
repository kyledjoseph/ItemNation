# ShopGab

Social shopping, powered by Bootstrap & FuelPHP.

## Installation

#### 1. Configure Apache and MySQL

* Ports: set Apache to 80 and MySQL to 3306
* Apache: set the Document Root to your Sites folder (ex: /Users/your_username/Sites)
* PHP: ensure that you're using PHP 5.4 or greater

#### 2. Clone Github Repository

git clone git@github.com:kyledjoseph/shopgab.git
cd shopgab
git submodule init
git submodule update
cd fuel/app
php composer.phar self-update
php composer.phar update
cd ../..
curl get.fuelphp.com/oil | sh
oil r install

#### 3. Import MySQL dump

The latest sql dump can be found in the _db folder in the root of the repository. I recommend using [Sequel Pro](http://www.sequelpro.com) to connect to the database. The default MySQL setting for both the username and the password is root.

#### 4. Setup Local Domain

Edit /private/etc/hosts (you will need to use sudo).

    sudo nano /private/etc/hosts
    > password:

Point `127.0.0.1` to `itemnation.dev` in your hosts file.

    127.0.0.1 itemnation.dev

## Development

Our development cycle is a hybrid between [GitHub flow](http://scottchacon.com/2011/08/31/github-flow.html) and [gitflow](http://nvie.com/posts/a-successful-git-branching-model/), and can be broken into a eight-step process:

#### 1. Milestone

Milestones dictate the release of issue resolutions, and can fall into one of three categories:

* **[Urgent:](https://github.com/kyledjoseph/itemnation/issues?milestone=20)** Release an issue resolution as soon as possible.
* **[Weekly:](https://github.com/kyledjoseph/shopgab/issues/milestones)** Create a pull request to release an issue resolution on Wednesday.
* **[Wishlist:](https://github.com/kyledjoseph/shopgab/issues?milestone=9&state=open)** Wait until these issues are assigned a weekly or urgent milestone.

#### 2. Issue

Always have an issue for what you're working on. Issue titles should be actionable ("Fix broken page" instead of "Page is broken") and issue names should be similar to the following examples:

* **Bug**
  * Fix dashboard
  * Fix chat bug
  * Fix logout bug
* **Feature**
  * Make website responsive
  * Build product API
  * Delete all databases

#### 3. Branch

Always have an branch for what you're working on. For example, to work on issue #12345 you'd use the following:

```
git checkout master      # switch to master
git checkout -b 12345    # create a new branch
git push -u origin 12345 # push to github and set upstream
```

#### 4. Commit

Commit to your branch and push.

```
git commit -m "Delete everything we've ever worked on" # descriptive commit message
git push # push to github
```

#### 5. Merge into `test`

Merge `test` into your issue resolution branch.

```
git fetch origin   # fetch new changes
# WEEKLY ISSUES ONLY
git checkout 12345 # checkout issue branch
git merge test     # make sure your branch is up to date
# /WEEKLY ISSUES ONLY
git checkout test  # checkout test branch
git merge 12345    # merge changes
```

#### 6. Test

Test any features that your branch may have affected.

```
git push test test # deploy to test server
```

* Login
* View Quest
* Add Quest
* Comment
* Rate
* Chat
* Add Product
* etc.

#### 7. Merge `master`

Merge `master` into your branch.

```
git fetch origin
git checkout 12345
git merge master
git push origin 12345
```

#### 8. Pull request

Create a pull request from your branch into `master`. If at any point your issue isn't able to be automatically merged, repeat step 7.

```
hub pull-request -i 12345 -b kyledjoseph:master -h kyledjoseph:12345
```

If you're solving an [urgent](https://github.com/kyledjoseph/itemnation/issues?milestone=20) issue, you can merge this immediately and deploy. Otherwise, the pull request will be merged at the weekly meeting.