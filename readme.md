# CASINOBIT.IO

##bitcoind

####Getting started

In order the software to work with the bictoin, you need to install, start and run local bitcoin node. You can do that by running headerless bitcoind.
You can download the bitcoind [here](https://bitcoin.org/bin/bitcoin-core-0.19.1/bitcoin-0.19.1-x86_64-linux-gnu.tar.gz).
In order to install bitcoind, read the README.md file in the archive, and follow the instructions.
If you will keep 'prune' parameter as it is in the resources/bitcoin.conf file, the node will require you to have less than 8Gb of free space in your system.

#####Update the resources/bitcoin.conf file:
- Set rpcbind to your public IP in 'global' section for live bictoin network
- Set rpcbind in the '[test]' section to your public IP for the TestNet bitcoin network (make sure that 'testnet' parameter in the config file is set to '1')
- Set rpcauth in order to allow access to you node via rpc. You should specify <USERNAME>:<SALT>$<HASH> string. You can generate the string and a password with the ./share/rpcauth/rpcauth.py script in the Bitcoin Core repository.

#####Update .env file:
- Set BITCOIN_HOST to the same public IP as you did in the resources/bitcoin.conf for rpcbind
- Set BITCOIN_USERNAME to the same username as you did in the resources/bitcoin.conf for rpcauth
- Set BITCOIN_PASSWORD to the password, you received after running the ./share/rpcauth/rpcauth.py

#####Running the node
You should either add a cron job or run the node manually
```
bitcoind -conf=/root/resources/bitcoin.conf
```
The absolute path to the config file should be specified.
You can find more info about bitcoin.conf variables and their values [here](https://jlopp.github.io/bitcoin-core-config-generator/).