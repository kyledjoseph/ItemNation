# ShopGab

Social shopping, powered by Bootstrap & FuelPHP.

## Installation

#### 1. Configure Apache and MySQL

* Ports: set Apache to 80 and MySQL to 3306
* Apache: set the Document Root to your Sites folder (ex: /Users/your_username/Sites)
* PHP: ensure that you're using PHP 5.4 or greater

#### 2. Clone Github Repository

```
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
```

#### 3. Import MySQL dump

The latest SQL dump can be found in the _db folder in the root of the repository. I recommend using [Sequel Pro](http://www.sequelpro.com) to connect to the database.

#### 4. Set up Local Domain

Edit /private/etc/hosts (you will need to use sudo).

    sudo nano /private/etc/hosts
    > password:

Point `127.0.0.1` to `itemnation.dev` in your hosts file.

    127.0.0.1 itemnation.dev
    
#### 5. Set up remotes

```
git remote add deploy root@shopgab.com:/opt/git/shopgab.git
git remote add test root@shopgab.com:/opt/git/test.git
```

## Development

Our development cycle is a hybrid between [GitHub flow](http://scottchacon.com/2011/08/31/github-flow.html) and [gitflow](http://nvie.com/posts/a-successful-git-branching-model/), and can be broken into a six-step process:

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

Always have an branch for the issue you're working on. For example, to work on issue #12345 you'd use the following:

```
git checkout master
git checkout -b 12345
git push -u origin 12345
```

#### 4. Commit

Commit to your branch and push.

```
git commit -m "Delete everything we've ever worked on"
git push
```

#### 5. Merge into `test`

When you're ready to test your code, merge your branch into `test` and push.

```
git checkout test
git merge 12345
git push
git push test test
```

#### 6. Pull request

Create a pull request from your branch into `master`.

```
hub pull-request -i 12345 -b kyledjoseph:master -h kyledjoseph:12345
```

If your pull request can't be merged automatically, update you branch with changes from `master`.

```
git checkout master
git pull
git checkout 12345
git pull 12345
git merge master
git push origin 12345
```

If you're solving an [urgent](https://github.com/kyledjoseph/itemnation/issues?milestone=20) issue, you can merge this immediately and deploy. Otherwise, the pull request will be merged at the weekly meeting.