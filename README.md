# XenForo2-AddOnInstaller

Install via zip & git 

Install via git requires;
- git CLI client installed.
- The forum in development mode.
- Opinionated git repository layout.

## File system support

Add-on files are stored under the 'addOn-files' filesystem namespace.
Local storage is controlled with the configuration variable 
```$config['addOnDataPath']```
Which has the default: 
```install/addons/```