# LeadsNearby Schema Options

## Intro

Plugin to extend and customize Yoast SEO schema functionality.

As of version 11, Yoast SEO has very good schema support. Rather than maintain a plugin to manage schema independently, we decided to use our plugin to extend/customize the schema output by Yoast.

By default Yoast's "Organization" type is too broad for our clients so the goal is to narrow that down to something more specific.

## What's Included

An options page with the following options to help narrow down Yoast's schema:

* **Company type** - Default is LocalBusiness but other options are present including Electrician, HVACBusiness, etc etc
* **Price Range** - $ up to $$$$ - default is $$
* **Address**
* **Telephone Number**
* **Email Address**

All filled out options will be added to Yoast schema graph via the wpseo_schema_organization filter