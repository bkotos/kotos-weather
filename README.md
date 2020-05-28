# Kotos Weather
## Overview
This is a wordpress plugin that exposes a weather widget.

After installing this plugin, the widget can be added to your site by visiting `Appearance` -> `Widgets`.

This widget comes out of the box with an Open Weather Map API key that I have pre-configured.  However,
this can be replaced in the settings page for this plugin.

## Prerequisites
- **PHP** >= `7.2`
- **WordPress** >= `5.4.1`

## Technical Design Notes
This code base makes use of several design patterns and technical best practices, including:
 - This widget uses **entities** instead of associative arrays.  I leverage these to strongly type API responses, as 
 well as various domain objects such as the settings configurable in this plugin.
 - **Adapter** classes are leveraged heavily, to allow for loose coupling between the business logic
 and global functions both from the WordPress API and from PHP's standard library.  This is a best practice
 I like to follow as it allows for more extensible, maintainable, and testable code.
 - All classes use **dependency injection** and are wired using an **inversion of control** container 
 named `ServiceProvider`.
