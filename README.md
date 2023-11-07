# Simple PHP Workflow with Laravel Mix.

## Install

- `npm i`

## Features

- Copy `src/assets/*/` to `www/assets/*/` folder.
- Compile SASS `src/styles/app.scss` to `www/styles` folder.
- Bundle and transpile JS `src/scripts/app.js` to `www/scripts` folder.
- Create sources maps.
- Run a dev web server with browsersync.

## Commands

- `npm start` : build on files changes, launch a dev server with browsersync.
- `npm run build` : clean, lint and build the project.
- `npm run clean` : clean the `dist` folder.

## Warning

Not intended to be used in production since it do not follow best practices or security practices.  
For school project purpose only!
