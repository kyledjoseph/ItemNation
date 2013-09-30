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

Edit /private/etc/hosts (you will need to use sudo) ex:

    sudo nano /private/etc/hosts
    > password:

Add the following line to the end of the hosts file:

    127.0.0.1 itemnation.dev

## Development

Our development cycle is a hybrid between [GitHub flow](http://scottchacon.com/2011/08/31/github-flow.html) and [gitflow](http://nvie.com/posts/a-successful-git-branching-model/), and can be broken into a twelve-step process:

#### 1. Milestone

Milestones dictate the release of issue resolutions, and can be either on the weekly cycle (Wednesdays during our development meeting) or off-cycle (as soon as possible). The weekly milestones contain issues that should be completed by Tuesday at midnight, whereas the [urgent](https://github.com/kyledjoseph/itemnation/issues?milestone=20) milestone contains issues that should be completed as soon as possible. We also have a [Wishlist](https://github.com/kyledjoseph/itemnation/issues?milestone=9) for issues that we'd eventually like to resolve, but aren't currently scheduled.

#### 2. Issue

**Always** have an *issue* for what you're working on. Issue titles should be actionable ("Fix broken page" instead of "Page is broken") and issue names should be similar to the following examples:

* **Bug**
  * Fix dashboard
  * Fix chat bug
  * Fix logout bug
* **Feature**
  * Make website responsive
  * Build product API
  * Delete all databases

#### 3. Branch

**Always** have an *branch* for the branch you're working on. For example, to work on issue #12345 you'd use the following:

```
git checkout master      # switch to master
git checkout -b 12345    # create a new branch
git push -u origin 12345 # push to github and set upstream
```

#### 4. Commit

Commit to your branch, and push.

```
git commit -m "Delete everything we've ever worked on" # descriptive commit message
git push # push to github
```

#### 5. Pull Request to `test`

When an issue resolution is completely finished, convert the issue into a pull request. **Always** request for your branch to be merged into `test`.

```
hub pull-request -i 12345 -b kyledjoseph:test -h kyledjoseph:12345
```

If you're solving a weekly issue, you're done! Your pull request will be merged into `test` after QA at the weekly meeting.

If you're solving an [urgent](https://github.com/kyledjoseph/itemnation/issues?milestone=20) continue to step six.

#### 6. Merge to `test`

[Merge one pull request](https://github.com/kyledjoseph/shopgab/pulls) at a time. If necessary, do this through the CLI:

```
git fetch origin   # fetch new changes
git checkout 12345 # checkout issue branch
git merge test     # make sure the branch is up to date
git checkout test  # checkout test branch
git merge 12345    # merge changes
```

#### 7. Deploy to `test`

Deploy this merge to the testing server:

```
git checkout test    # checkout test branch
git pull origin test # pull recent changes
git push test test   # push to test server
```

#### 8. Test

Test any features that your issue branch may have affected:

* Login
* View Quest
* Add Quest
* Comment
* Rate
* Chat
* Add Product
* etc

#### 9. Pull request to `master`

Send a pull request to merge your issue branch (**not** `test`) into `master`, using either the [GUI](https://github.com/kyledjoseph/shopgab/compare/master...master) or the CLI:

```
hub pull-request -b kyledjoseph:master -h kyledjoseph:12345
```

#### 10. Merge to `master`

[Merge your pull request](https://github.com/kyledjoseph/shopgab/pulls) at a time. If necessary, do this through the CLI:

```
git fetch origin   # fetch new changes
git checkout 12345 # checkout issue branch
git merge test     # make sure the branch is up to date
git checkout test  # checkout test branch
git merge 12345    # merge changes
```

#### 11. Deploy to `master`

Deploy this merge to the deployment server:

```
git checkout master    # checkout test branch
git pull origin master # pull recent changes
git push deploy master # push to test server
```

#### 12. Relax

You made it. You survived. Refill your coffee and move to the next issue.