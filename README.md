Git global setup
git config --global user.name "Kanokpit Yowaratch"
git config --global user.email "kanokpit.y@gmail.com"

Create a new repository
git clone git@gitlab.com:kanokpit.y/dooball-online.git
cd dooball-online
touch README.md
git add README.md
git commit -m "add README"
git push -u origin master

Push an existing folder
cd existing_folder
git init
git remote add origin git@gitlab.com:kanokpit.y/dooball-online.git
git add .
git commit -m "Initial commit"
git push -u origin master

Push an existing Git repository
cd existing_repo
git remote rename origin old-origin
git remote add origin git@gitlab.com:kanokpit.y/dooball-online.git
git push -u origin --all
git push -u origin --tags# dooball-th
