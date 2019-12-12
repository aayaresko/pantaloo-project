# CASINOBIT.IO

Change the file mandatory - if you want to go to production to be able to deploy in production , you need your IP to be whitelisted (ask for it).

# Branches

- Master is the production branch - Development is the local dev branch

# Rules

- Develop in Development - When development is tested on https://dev.casinobit.io - and you are satisfied (passed QA) - Then, merge with master, and it will deploy automatically in production (https://www.casinobit.io)

# Set up your development git repo

set up : 
git init 
git pull https://<credentials>@github.com/bciosec/casinobit.git development 
git remote add origin https://github.com/bciosec/casinobit.git 
git fetch 
git checkout development 

pushing : git push origin development 

To go in production, you will have to submit a merge request (development->master)

# IMPORTANT :

1. DB Changes will need to be done manually on Staging / Production environment (Both must be in synchronization)
2. RPC calls to the node will not work from development - They will be enabled on request
3. Change on static assets will need cloudflare cache clearing


