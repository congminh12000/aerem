
var __eae_open = function (str) {
    document.location.href = __eae_decode(str);
};

var __eae_decode = function (str) {
    return str.replace(/[a-zA-Z]/g, function(c) {
        return String.fromCharCode(
            (c <= "Z" ? 90 : 122) >= (c = c.charCodeAt(0) + 13) ? c : c - 26
        );
    });
};

var __eae_decode_emails = function () {
    var __eae_emails = document.querySelectorAll(".__eae_r13");

    for (var i = 0; i < __eae_emails.length; i++) {
        __eae_emails[i].textContent = __eae_decode(__eae_emails[i].textContent);
    }
};

if (document.readyState !== "loading") {
    __eae_decode_emails();
} else if (document.addEventListener) {
    document.addEventListener("DOMContentLoaded", __eae_decode_emails);
} else {
    document.attachEvent("onreadystatechange", function () {
        if (document.readyState !== "loading") __eae_decode_emails();
    });
}

var __eae_r47 = function (str) {
    var r = function (a, d) {
        var map = "!\"#$%&'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~";

        for (var i = 0; i < a.length; i++) {
            var pos = map.indexOf(a[i]);
            d += pos >= 0 ? map[(pos + 47) % 94] : a[i];
        }

        return d;
    };

    window.location.href = r(str, "");
};

document.addEventListener("copy", function (event) {
    if (
        NodeList.prototype.forEach === undefined ||
        Array.from === undefined ||
        String.prototype.normalize === undefined ||
        ! event.clipboardData
    ) {
        return;
    }

    var encoded = [];
    var copiedText = window.getSelection().toString().trim();

    document.querySelectorAll(
        "." + document.querySelector("style[data-eae-name]").dataset.eaeName
    ).forEach(function (item) {
        encoded.push(item.innerText.trim());
    });

    if (! encoded.length) {
        return;
    }

    encoded.filter(function (value, index, self) {
        return self.indexOf(value) === index;
    }).map(function (item) {
        return {
            original: new RegExp(item.replace(/[-\/\\^$*+?.()|[\]{}]/g, "\\$&"), "ig"),
            reverse: Array.from(item.normalize("NFC"))
                .reverse()
                .join('')
                .replace(/[()]/g, "__eae__")
        };
    }).forEach(function (item) {
        copiedText = copiedText.replace(
            item.original,
            item.reverse.replace("__eae__", "(").replace("__eae__", ")")
        )
    });

    event.clipboardData.setData("text/plain", copiedText);
    event.preventDefault();
});
