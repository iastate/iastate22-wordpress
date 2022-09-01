# Privacy Consent

This component prevents any scripts that create cookies from initially executing until a user has accepted the terms of the site's Privacy Policy.

## Determining Which Scripts To Block

The `PrivacyConsent` class exposes a static public method called `findNonCompliantScripts` that can be invoked in order to determine which scripts are actually creating cookies. An example implementation would be appending the following to `src/js/index.ts`:

```
import { PrivacyConsent } from  "../components/privacy-consent/privacy-consent";
...
PrivacyConsent.findNonCompliantScripts();
```

Upon invoking this function, `console.trace` stack traces will appear in the JS Console showing which scripts have modified `document.cookie`.

## Blocking Scripts

Once the scripts that need to be blocked have been determined, you'll need to add a `YETT_BLACKLIST` array as high in the `head` tag as possible with offending domains specified. You'll also need to add the script tag to load Yett in from a CDN. This will prevent any scripts loaded from the provided domains from loading until the user has accepted the terms. An example implementation would be:

```
<script>
    window.YETT_BLACKLIST = [
        /addthis\.com/
    ];
</script>
<script src="//unpkg.com/yett"></script>
```

If possible, adding `type="javascript/blocked"` to any necessary script tags is preferred and, in the cases of inline script tags that aren't pulling in code from an external URL, it's required to provide this attribute to prevent execution. An example of a script where this is required would be a Facebook Tracking Pixel.

For more information and documentation on Yett, please consult [the Github Project](https://github.com/snipsco/yett).

## Implementing the Component

The Privacy Consent component should be placed as close to the bottom of the `body` tag as possible.