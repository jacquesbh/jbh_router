# The Jbh Router, your routes on Magento

## Use it

### Static url

*   URL: `http://example.org/foo.hmtl`
*   Match: `http://example.org/foo/index/index`
*   `config.xml`:
    ```xml
    <jbh_router>
        <my_router>
            <type>static</type>
            <route>foo.html</route>
            <routers>
                <foo>
                    <use>standard</use>
                    <args>
                        <module>My_Module</module>
                        <frontName>foo</frontName>
                    </args>
                </foo>
            </routers>
            <module>foo</module>
            <controller>index</controller>
            <action>index</action>
        </my_router>
    </jbh_router>
    ```

### Regex URL

*   URL:
    *   `http://example.org/helloworld/foo.html`
    *   `http://example.org/helloworld/bar.html`
    *   `http://example.org/helloworld/baz.html`
*   Match:
    *   `http://example.org/hello/index/index/key/foo`
    *   `http://example.org/hello/index/index/key/bar`
    *   `http://example.org/hello/index/index/key/baz`
*   `config.xml`:
    ```xml
    <jbh_router>
        <my_router>
            <type>regex</type>
            <route>helloworld/(foo|bar|baz)\.html</route>
            <reverse>helloworld/%1$s.html</reverse>
            <map>
                <key>1</key>
            </map>
            <routers>
                <hello>
                    <use>standard</use>
                    <args>
                        <module>My_Module</module>
                        <frontName>hello</frontName>
                    </args>
                </hello>
            </routers>
            <module>hello</module>
            <controller>index</controller>
            <action>index</action>
        </my_router>
    </jbh_router>
    ```

## Get a router named `foo`

```php
<?php
$router = Mage::helper('jbh_router')->getRouter('foo');

// is match ?
$router->isMatch(); // bool

// assemble route
$router->assemble($params);
```

## Need dynamism?

Example with config:

```xml
<jbh_router>
    <my_router>
        <type>static</type>
        <route config="my_module/my_router/route" /> <!-- here -->
        <routers>
            <foo>
                <use>standard</use>
                <args>
                    <module>My_Module</module>
                    <frontName>foo</frontName>
                </args>
            </foo>
        </routers>
        <module>foo</module>
        <controller>index</controller>
        <action>index</action>
    </my_router>
</jbh_router>
```

Example with helper:

```xml
<jbh_router>
    <my_router>
        <type>static</type>
        <route helper="my_module/router::getRoute" /> <!-- here -->
        <router>
            <foo>
                <use>standard</use>
                <args>
                    <module>My_Module</module>
                    <frontName>foo</frontName>
                </args>
            </foo>
        </routers>
        <module>foo</module>
        <controller>index</controller>
        <action>index</action>
    </my_router>
</jbh_router>
```
