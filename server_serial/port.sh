#!/bin/bash
echo "Portnumber ttyACM_"
read port

sudo chmod a+rw "/dev/ttyACM$port"
