TAG=`cat src/version.txt`
echo $TAG ;
cd $HOME/devspace/mara-pub ;
./rocket_pub.sh  --prj xrest_utls --tag $TAG --host $*
