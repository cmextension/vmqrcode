#!/bin/bash

pkgFileName="pkg_vmqrcode_1.0.0.zip"
currentPath=$(pwd)
releasesDirPath="$currentPath/releases"
pkgDirPath="$currentPath/pkg_vmqrcode"
subPkgDirPath="$pkgDirPath/packages"
pkgFilePath="$pkgDirPath/pkg_vmqrcode.xml"

# Create the directory where the final ZIP file is stored
if [ ! -d $releasesDirPath ]; then
  echo "$releasesDirPath doesn't exist, create it..."
  if mkdir -p $releasesDirPath; then
    echo "Created $releasesDirPath"
  else
    echo "Failed to create $releasesDirPath, exiting..."
    exit 1
  fi
fi

# The directory where the package XML file and its source must exist
# Remove the source if it already exists
if [ ! -d $pkgDirPath ]; then
  echo "$pkgDirPath doesn't exist, exiting..."
  exit 1
fi

if [ ! -f $pkgFilePath ]; then
  echo "$pkgFilePath doesn't exist, exiting..."
  exit 1
fi


if [ -d $subPkgDirPath ]; then
  echo "$subPkgDirPath already exists, deleting..."

  if rm -rf $subPkgDirPath; then
    echo "$subPkgDirPath has been deleted"
  else
    echo "Failed to delete $subPkgDirPath, exiting..."
    exit 1
  fi
fi

# Copy the component's admin code
comDirPath="$subPkgDirPath/com_vmqrcode"

if ! mkdir -p $comDirPath; then
  echo "Failed to create $comDirPath, exiting..."
  exit 1
fi

comSrcPath="$currentPath/administrator/components/com_vmqrcode/*"
comDesPath="$comDirPath/admin"

if ! mkdir -p $comDesPath; then
  echo "Failed to create $comDesPath, exiting..."
  exit 1
fi

cp -rf $comSrcPath $comDesPath

# Move the component XML file
xmlSrcPath="$comDesPath/vmqrcode.xml"
xmlDesPath="$comDirPath/vmqrcode.xml"

mv $xmlSrcPath $xmlDesPath

# Copy the plugin
plgSrcPath="$currentPath/plugins/system/vmqrcode/*"
plgDesPath="$subPkgDirPath/plg_system_vmqrcode"

if ! mkdir -p $plgDesPath; then
  echo "Failed to create $plgDesPath, exiting..."
  exit 1
fi

cp -rf $plgSrcPath $plgDesPath

# Zip the package
pkgFilePath="$releasesDirPath/$pkgFileName"

if [ -f $pkgFilePath ]; then
  echo "$pkgFilePath already exists, deleting..."

  if rm $pkgFilePath; then
    echo "$pkgFilePath has been deleted"
  else
    echo "Failed to delete $pkgFilePath, exiting..."
    exit 1
  fi
fi

cd $pkgDirPath

if zip -rq $pkgFilePath *; then
  echo "$pkgFilePath has been created"
else
  echo "Failed to create $pkgFilePath"
fi

