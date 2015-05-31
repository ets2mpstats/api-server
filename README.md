# API Server


## Configuration

Ideally you shouldn't need to do much changes to ```<approot>/conf/app.yml``` because when you run ```<approot>/bin/install``` will take care of the settings and exposes all of the required configuration variables and *attempts* to ensure that paths exists and are writeable by php before writing the config to disk.

For nginx there's an example config in ```<approot>/exampleconfigs/default``` which is also used by vagrant when developing, this can be used as a template for production systems as well. API Server is being developed against Ubuntu but is targeted to be deployed to any nix system around, such as BSD and Linux

### Available Logging levels

    DEBUG = 100
    INFO = 200
    NOTICE = 250
    WARNING = 300
    ERROR = 400
    CRITICAL = 500
    ALERT = 550
    EMERGENCY = 600

The levels correspond mostly to the standard syslog levels, but doesn't use the standard 0-7 numbering scheme.
