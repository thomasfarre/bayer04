#!/bin/sh

yarn add @symfony/webpack-encore --dev
yarn install
yarn encore dev --watch
