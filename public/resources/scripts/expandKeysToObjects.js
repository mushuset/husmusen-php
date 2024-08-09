/**
 * Expands keys like "foo.bar" on objects into objects themselves.
 *
 * This also expands any objects stored as values in the given object.
 *
 * Example:
 *
 * ```javascript
 * const foo = {
 *     "foo": "value",
 *     "bar.baz": "value",
 *     "baz": { "foo.bar": "value" }
 * }
 *
 * const newFoo = expandKeysToObjects(foo)
 *
 * // value of newFoo
 * const value = {
 *     "foo": "value"
 *     "bar": {
 *         "baz": "value"
 *     },
 *     "baz": {
 *         "foo": {
 *              "bar": "value"
 *          }
 *     }
 * }
 * ```
 * @param {object} objectToExpand The object which keys should be expanded.
 * @returns {object} The new object with the expanded keys.
 */
function expandKeysToObjects(objectToExpand) {
    /**
     * Store the resulting object in `result`
     */
    let result = {}

    for (const key of Object.keys(objectToExpand)) {
        /**
         * Value stores the value of said key.
         * If said value is an object, its keys will also be expanded.
         */
        const value = typeof objectToExpand[key] === "object" ? expandKeysToObjects(objectToExpand[key]) : objectToExpand[key]

        // If the key does not contain any dots, it does not need to be expanded. Therefore just add it to the result.
        if (!key.includes(".")) {
            result[key] = value
            // Skip to the next iteration; nothing left to do for this key.
            continue
        }

        // Get the `parentKey` and all `subKeys` from the key.
        // The `parentKey` is the first key of after splitting the key at all dots (`.`).
        const [parentKey, ...subKeys] = key.split(".")

        // If the `parentKey` property is undefined, set it to be an object, so we can assign `subKeys` to it.
        if (!result[parentKey]) {
            result[parentKey] = {}
        }

        // If there's only one `subKeys`, immediately assign it to the value.
        if (subKeys.length === 1) {
            result[parentKey][subKeys[0]] = value
            // Skip to the next iteration; nothing left to do for this key.
            continue
        }

        // Now get the first `subKey` and the rest of the keys.
        const [subKey, ...rest] = subKeys
        // Join the rest of the keys together with a dot.
        const restKey = rest.join(".")

        // If the `subKey` property is undefined, set it to be an object, so we can assign more `subKeys` to it.
        if (!result[parentKey][subKey]) {
            result[parentKey][subKey] = {}
        }

        // Now, recursively run `expandKeysToObjects` on the `restKey` and its value to recursively expand its keys as well.
        const moreData = expandKeysToObjects({ [restKey]: value })

        // Now iterate over all the keys of `moreData`, and apply them to the result.
        for (const moreDataKey of Object.keys(moreData)) {
            // If said key is not an object, just apply the value directly onto the result.
            // Otherwise, if it is an object, merge it with the result, as to not lose any keys under the same key.
            // E.g. make it possible to add "a.a.a.a.a" without overwriting "a.a.a.a.b".
            if (typeof moreData[moreDataKey] !== "object") {
                result[parentKey][subKey][moreDataKey] = moreData[moreDataKey]
            } else {
                const currentValue = result[parentKey][subKey][moreDataKey]

                result[parentKey][subKey][moreDataKey] = {
                    ...currentValue,
                    ...moreData[moreDataKey]
                }
            }
        }
    }

    // Now the result will be an object with the keys expanded to more objects.
    return result
}

export default expandKeysToObjects
