# husmusen-php

This is an implementation of [Husmusen](https://github.com/mushuset/docs) in PHP. Description from [mushuset/husmusen](https://github.com/mushuset/husmusen):

> Husmusen (the house mouse) is an inventory managment system protocol for museums and other institutions to publish information about their items and inventory for the public viewing. Husmusen aims to be as simple as possible, while still offering functionality that is important or even *required*. **This is an example implementation of the protocol.** That means that the code hopefully will be easy to read and understand, as well as contain helpful comments. Although *Husmusen* is technically only aimed at Swedish institutions (for now), all comments and code will be in English, simply because I think it is a hot mess to have the code in English and comments in another language. The UI for a control panel example that is included is in Swedish though.

## Requirements

Husmusen-PHP requries:

* `PHP` version 8 ([Installation guide](https://www.php.net/manual/en/install.php))
* `composer` ([a dependency/package manager for PHP](https://getcomposer.org/))
* `mariadb` or `mysql`

### How to obtain a MariaDB server?

There are two ways: host yourself or buy hosting from someone else. Various web hotels provide MariaDB hosting, but for testing I recommend hosting a server locally yourself, as that is free. See the links below for your platform:

* [Windows](https://www.mariadbtutorial.com/getting-started/install-mariadb/)
* [Arch Linux](https://wiki.archlinux.org/title/MariaDB)
* [Ubuntu](https://hevodata.com/learn/installing-mariadb-on-ubuntu/)
* [Fedora](https://docs.fedoraproject.org/en-US/quick-docs/installing-mysql-mariadb/)

If I didn't mention your platform you can probably just search for `how to install mariadb <my-platform>` where you replace `<my-platform>` with your platform.

## Setting up a development environment

### Step 1: Obtaining the source code

To setup a development environment, simply clone the repository:

```bash
git clone https://github.com/mushuset/husmusen-php
```

> [!TIP]
> You can also download a ZIP file by clicking the green button above where it says `Code ▼` and then clicking on `Download ZIP`. (Or by simple clicking [on this link](https://github.com/mushuset/husmusen-php/archive/refs/heads/main.zip).)

### Step 2: Installing dependencies

After you have obtained the source code, enter the root directory of the project.

To install all the dependencies, simply run the following command:

```bash
composer install
```

### Step 3: Creating a `.env` file

For the server to work properly, a `.env` file is needed. To create one, simply copy the contents of `.env.example` into `.env`.

### Step 4: Running a server

The easiest way to run a server for development purposes is to use the included `artisan` script/command. Simply run:

```bash
php artisan serve
```

> [!TIP]
> If you want, you can specify port and host for the server with `--port=12345` (change `12345` to whatever port you want) and `--host=0.0.0.0`.
> Setting the `--host` to `0.0.0.0` is useful if you want to reach your development server from more than just `loclhost` (`127.0.0.1`).
> You can also use `-vvv` to get more verbose output. (However, I believe that I've never seen this make a difference...)

### Step 5: First time setup

Before you start developing on Husmusen, you should make sure your database connection is established and the tables necessary for Husmusen's operation are created.

To do this visit `/setup` on your development instance of Husmusen. Most likely you will find it at [localhost:8000/setup](http://localhost:8000/setup) ([127.0.0.1:8000/setup](http://127.0.0.1:8000/setup)). This is assuming you haven't specified another port, if you have, replace `8000` with your port.

Now, on the setup page follow the instructions (written in Swedish), but **DO NOT click the button on step 6**! Since you are creating a development environment you don't want to set the instance up as a production environment.

### Step 6: You are now ready to develop

You are now done with the setup and you can start working on development.

## Setting up a production environment

### Step 1, 2, 3: Same as development environment

The first three steps are the same as for setting up a development environment, so follow steps 1–3 above.

### Step 4: Copying the project to your server

> [!WARNING]
> This part of the guide is not yet done. It will be added later.
