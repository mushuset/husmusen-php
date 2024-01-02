export function getColorForName(name) {
    // Generate a hash of the name
    let hash = 0;
    for (let i = 0; i < name.length; i++) {
        hash = name.charCodeAt(i) + ((hash << 5) - hash);
    }

    // Convert the hash to a color
    const hue = hash % 360;
    const saturation = 50 + (hash % 255);
    const lightness = 60 + (hash % 30);

    // Convert HSL color to RGB
    const { r, g, b } = hslToRgb(hue, saturation, lightness);

    // Check contrast against #241f1c
    const contrast = getContrastRatio({ r, g, b }, { r: 36, g: 31, b: 28 });
    const color = contrast >= 4.5 ? `rgb(${r}, ${g}, ${b})` : '#000000'; // fallback to black instead of white

    // Return the color as a CSS string
    return color;
}

// Helper function to convert HSL color to RGB
function hue2rgb(p, q, t) {
    if (t < 0) t += 1
    if (t > 1) t -= 1
    if (t < 1 / 6) return p + (q - p) * 6 * t
    if (t < 1 / 2) return q
    if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6
    return p
}

// Helper function to calculate contrast ratio
function getContrastRatio(color1, color2) {
    const luminance1 = getRelativeLuminance(color1)
    const luminance2 = getRelativeLuminance(color2)
    const brighter = Math.max(luminance1, luminance2)
    const darker = Math.min(luminance1, luminance2)
    return (brighter + 0.05) / (darker + 0.05)
}

// Helper function to calculate relative luminance
function getRelativeLuminance({ r, g, b }) {
    const [R, G, B] = [r, g, b].map((c) => {
        const sRGB = c / 255
        return sRGB <= 0.03928 ? sRGB / 12.92 : ((sRGB + 0.055) / 1.055) ** 2.4
    })
    return 0.2126 * R + 0.7152 * G + 0.0722 * B
}

// Helper function to convert HSL color to RGB
function hslToRgb(h, s, l) {
    // Convert h, s, l values to range 0-1
    h /= 360;
    s /= 100;
    l /= 100;

    let r, g, b;

    if (s === 0) {
        r = g = b = l; // achromatic
    } else {
        const hue2rgb = (p, q, t) => {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1 / 6) return p + (q - p) * 6 * t;
            if (t < 1 / 2) return q;
            if (t < 2 / 3) return p + (q - p) * (2 / 3 - t) * 6;
            return p;
        };

        const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        const p = 2 * l - q;
        r = hue2rgb(p, q, h + 1 / 3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1 / 3);
    }

    return { r: Math.round(r * 255), g: Math.round(g * 255), b: Math.round(b * 255) };
}