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

The latest SQL dump can be found in the _db folder in the root of the repository.

#### 4. Set up Local Domain

Edit your hosts file at `/private/etc/hosts`.

    sudo nano /private/etc/hosts

Point `127.0.0.1` to `shopgab.dev` in your hosts file.

    127.0.0.1 shopgab.dev
    
#### 5. Set up remotes

```
git remote add deploy root@shopgab.com:/opt/git/shopgab.git
git remote add test root@shopgab.com:/opt/git/test.git
```

## Development

Our development cycle is a hybrid between [GitHub flow](http://scottchacon.com/2011/08/31/github-flow.html) and [gitflow](http://nvie.com/posts/a-successful-git-branching-model/), and can be broken into a six-step process:

#### 1. Milestone

There are three types of milestones:

* **[QA:](https://github.com/kyledjoseph/shopgab/issues?milestone=23&state=open)** Completed issues to be reviewed and tested before deployment.
* **[Weekly:](https://github.com/kyledjoseph/shopgab/issues/milestones)** In-progress issues to be assigned to QA by 
* **[Wishlist:](https://github.com/kyledjoseph/shopgab/issues?milestone=9&state=open)** Issues that may be assigned in the future.

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
bin/gab branch 12345
```

#### 4. Commit

Add your changes, commit, push, and open a pull request. 

```
bin/gab commit
```

#### 5. Test

[Test your changes locally](http://shopgab.dev), then [test your changes](http://test.shopgab.com/) remotely.

```
bin/gab test
```

#### 6. Update

If your pull request can't be merged automatically, update your branch and solve any merge conflicts.

```
bin/gab update
```