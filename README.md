# Schema Options

Plugin to easily add schema to Wordpress site

### Shortcodes

[lnb-schema-areaserved]

Available attributes:
* **cities**: List of cities to be exploded into array
* **state**: State corresponding to cities attribute
* **url**: URL for link to page corresponding to cities attribute
* **type**: default is textblock
* **list_cols**: default is four

Example: [lnb-schema-areaserved cities="City1, City2, City3, City4, City5" state="NC" url="http://adomain.com/service-area/{city}-{state}-service/" type="list" list_cols="three"]
