# Bees In The Trap

Simple bee game written in PHP.

## Getting Started

Play Bees In The Trap locally or through Docker.

---

### Run the game in Docker

#### Steps

Run this command to build and run the container.

```
$ docker build -t johnfallis/beegame:1.0 . 
$ docker run -it johnfallis/beegame:1.0
```

#### Troubleshooting

```
$ docker run -v $PWD:/beegame -it johnfallis/beegame:1.0 /bin/sh
```

Then run the following inside the container to find the issue.
```
/beegame # make
```

---

### Run the game locally

#### Steps

Allow executing file as program

```
$ chmod +x beesinthetrap
```

Install dependencies.

```
$ composer install
```

Run the game.

```
$ ./beesinthetrap
```

#### Troubleshooting

Requires PHP 7.4.*

---
