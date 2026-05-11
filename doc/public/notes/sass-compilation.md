[Return to docs home page](../index.md)

# SASS Compilation

## Build:
Here we describe the SASS build process for Peanut/ConcreteCMS 9 
sites using a clone of the Atomik theme in the application/themes 
directory. 

The deployment file is:
- Austinquakers.org: application\themes\fma\css\skins\default.css 
- Scym.org: application\themes\peanut\css\skins\peanut.css

The term "local deployment directory" refers to the associated path
in your development environment.

The SASS version used is: 1.43.5 compiled with dart2js 2.14.4

## Setup

1. Install SASS
- Using Node.js: 
```
npm install -g sass
```
   - or see [SASS-Lang Install](https://sass-lang.com/install/)

2. Create /sass directory in the project root.
3. Copy web.root/concrete/bedrock/assets to /sass/assets
4. Create /sass/output directory
5. Get the powershell script from the Git repository copy to /sass

## Build scripts:
These are Windows PowerShell scripts. If you are using a different OS
such as MacOS, you should be able to adapt these to some other scripting
engine such as bash or Node.js.

- buildcss.ps1 - compile all scss from Fma theme and Bedrock. Result in sass\output
    is copied to the local deployment directory.
- deploycss.ps2 - replace the css file in the local deployment directory from sass\output

### Parameters
The buildcss script accepts two optional parameters. Used the full name and precede with a dash.
- -plain: Creates an uncompressed file. Compression is used if omitted.
- -verbose: Runs the compiler in "--verbose" mode which displays all warnings as well as errors.

## Adapt to other concreteCMS installations

Change the "constants" declarations in the scripts to conform to the directory
and filenames used in your site.

## Errors and Warnings
The reason we make a copy of the "bedrocks/assets" directory is to be able
to make any necessary changes, should compilation errors occur in the current
version. We have done so in the past, but right now there doesn't seem to be any
reason to do.

However, the current ConcreteCMS distribution SASS source files raise 
numerous deprecation warnings, the most common being the report that the 
"@import" rule is deprecated. 
See [Sass-lang: import deprecated](https://sass-lang.com/blog/import-is-deprecated/)

Due to the use of deprecated features a new version of the SASS compiler 
may break this build. If this happens, we hope ConcreteCMS will provide 
upgraded SASS files for Atomik and bedrock, which can be use to fix up our theme.  
Otherwise we will need to keep using an older release of the SASS compiler
or make our own changes to the files in the SASS/assets folder.

To see all the warnings use the "-verbose" parameter to the buildcss.ps1 script.

## Extra styles
We include a plain style sheet, application/themes/fma/css/extra.css.

You may use this stylesheet for quick fixs and temporary overrides.  However,
the best practice is to migrate any changes made to this file to the SASS
build system.

## Why not use WebPack?

ConcreteCMS recommends that theme developers who use Bedrock based themes adopt 
a build process using WebPack and Laravel Mix to bundle compile, minimize and 
bundle JavaScript and CSS.

See: [Introduction to Bedrock](https://documentation.concretecms.org/9-x/developers/working-themes/introduction-bedrock)

This seems to be overkill for our purposes.  The Peanut framework is not compatible
with WebPack and has its own modularization and real-time loading scheme. So we just need to 
compile the CSS and use the distributed JavaScript package for Atomik/ConcreteCMS.




