import checkIfLoggedIn from "./checkIfLoggedIn.js"
import checkSuccess from "./checkSuccess.js"
import expandKeysToObjects from "./expandKeysToObjects.js"
import husmusenOptions from "./husmusenOptions.js"

// If the user isn't logged in, redirect them to the login page.
// TODO: Maybe fix a pop-up instead?
checkIfLoggedIn().then().catch(() => window.location.replace(`${husmusenOptions.HUSMUSEN_MOUNT_PATH}/app/login`))

// Select all forms that have the `auto-rig` class.
const forms = document.querySelectorAll("form.auto-rig")
// Make sure said forms are handled in a special way:
for (const form of forms) {
    form.addEventListener(
        "submit",
        async (event) => {
            // Make sure the default way of handling submission is ignored.
            event.preventDefault()

            // Get all data.
            const formData = new FormData(form)

            // Read all keys and values into the `payload` variable.
            let formDataAsObject = {}
            formData.forEach(
                // If there are multiple values with the same name, we want to put them into an array.
                (value, key) => {
                    if (Array.isArray(formDataAsObject[key])) {
                        // If said value in `formDataAsObject` already is an array, push to it,
                        formDataAsObject[key].push(value)
                    } else if (formDataAsObject[key] !== undefined) {
                        // If the value is not undefined (aka it is defined), create an array from the previous and current value.
                        formDataAsObject[key] = [formDataAsObject[key], value]
                    } else {
                        // Else, the key is undefined, and therefore just assign the value to it.
                        formDataAsObject[key] = value
                    }
                }
            )

            // If a key is an array, and the first instance of an input element with said name has the `data-array-join` attribute,
            // join it with the value given in said attribute.
            for (const key of Object.keys(formDataAsObject)) {
                const inputElement = document.querySelector(`[name="${key}"]`)

                if (inputElement.hasAttribute("data-array-join") && Array.isArray(formDataAsObject[key])) {
                    formDataAsObject[key] = formDataAsObject[key].join(inputElement.getAttribute("data-array-join"))
                }
            }

            // Expand keys into objects (if needed)
            const payload = expandKeysToObjects(formDataAsObject)

            // Send a request using the action and method defined in the form-HTML element.
            // Also, send the payload as the body.
            fetch(
                form.getAttribute("action"),
                {
                    method: form.getAttribute("method"),
                    headers: {
                        "Husmusen-Access-Token": localStorage.getItem("api-token"),
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(payload)
                }
            )
                .then(checkSuccess)
                .then(
                    data => alert("Klar! Information: " + JSON.stringify(data))
                )
                .catch(
                    err => {
                        alert("Error! Kolla i konsolen för mer information.")
                        console.error(err)
                    }
                )
        }
    )
}

/**
 * This function creates a Buffer from a Blob.
 * @param {Blob} blob
 * @returns {Promise<Buffer>}
 */
function getFileDataBufferToDataURL(blob) {
    return new Promise(
        (resolve, reject) => {
            const reader = new FileReader()
            reader.onload = result => resolve(result.target.result)
            reader.readAsDataURL(blob)
            reader.onerror = err => reject(err)
        }
    )
}

// Set up the file creation form.
const fileCreationForm = document.querySelector("#file-creation-form")
fileCreationForm?.addEventListener(
    "submit",
    async event => {
        event.preventDefault()
        const formData = new FormData(fileCreationForm)
        const file = formData.get("fileDataBuffer")
        const fileMIME = file.type

        const rawFileData = document.querySelector("#file-data-buffer").files[0]
        const fileDataURL = await getFileDataBufferToDataURL(rawFileData)
            .catch(
                err => {
                    alert("Error! Kolla i konsolen för mer information.")
                    console.error(err)
                }
            )

        const payload = {
            name: formData.get("name"),
            description: formData.get("description"),
            license: formData.get("license"),
            relatedItem: formData.get("relatedItem"),
            type: fileMIME,
            fileDataURL
        }

        // console.dir({ fileDataBuffer, fileDataBufferJSON: Array.from(new Uint8Array(fileDataBuffer)) })

        fetch(
            fileCreationForm.getAttribute("action"),
            {
                method: fileCreationForm.getAttribute("method"),
                headers: {
                    "Husmusen-Access-Token": localStorage.getItem("api-token"),
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(payload)
            }
        )
            .then(checkSuccess)
            .then(
                data => alert("Klar! Information: " + JSON.stringify(data))
            )
            .catch(
                err => {
                    alert("Error! Kolla i konsolen för mer information.")
                    console.error(err)
                }
            )
    }
)

// Select all forms that have the `YAML` class.
const YAMLforms = document.querySelectorAll("form.YAML")
// Make sure said forms are handled in another special way:
for (const form of YAMLforms) {
    form.addEventListener(
        "submit",
        event => {
            // Make sure the default way of handling submission is ignored.
            event.preventDefault()

            // Get all data.
            const formData = new FormData(form)

            // Get the payload from the `YAML`-field.
            let payload = formData.get("YAML")

            // Send a request using the action and method defined in the form-HTML element.
            // Also, send the payload as the body.
            fetch(
                form.getAttribute("action"),
                {
                    method: form.getAttribute("method"),
                    headers: {
                        "Husmusen-Access-Token": localStorage.getItem("api-token"),
                        "Content-Type": "application/yaml"
                    },
                    body: payload
                }
            )
                .then(checkSuccess)
                .then(
                    data => alert("Klar! Information: " + JSON.stringify(data))
                )
                .catch(
                    err => {
                        alert("Error! Kolla i konsolen för mer information.")
                        console.error(err)
                    }
                )
        }
    )
}

// Make it so that the user can log out.
// This deletes the current API token.
document.querySelector("#log-out-form")
    ?.addEventListener(
        "submit",
        event => {
            event.preventDefault()
            localStorage.removeItem("api-token")
            localStorage.removeItem("api-token-valid-until")
            window.location.assign(`${husmusenOptions.HUSMUSEN_MOUNT_PATH}/app`)
        }
    )

// Handle the edit-file-form:
const editKeywordsForm = document.querySelector("#edit-keywords-form")
editKeywordsForm?.addEventListener(
    "submit",
    event => {
        event.preventDefault()

        const formData = new FormData(editKeywordsForm)
        const keywordsData = formData.get("newKeywordData")

        const payload = keywordsData
            .split("\n")
            // Make sure the line is in the format `<TYPE>: <KEYWORD>: <DESCRIPTION>
            .filter(line => line.match(/^\w+: .+: .+$/))
            .sort((a, b) => a.localeCompare(b))
            .map(
                line => {
                    const [type, word, ...description] = line.split(/: +/)
                    const keyword = {
                        type,
                        word,
                        description: description.join(": ")
                    }
                    return keyword
                }
            )


        fetch(
            editKeywordsForm.getAttribute("action"),
            {
                method: editKeywordsForm.getAttribute("method"),
                headers: {
                    "Husmusen-Access-Token": localStorage.getItem("api-token"),
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    keywords: payload
                })
            }
        )
            .then(checkSuccess)
            .then(
                data => alert("Klar! Information: " + JSON.stringify(data))
            )
            .catch(
                err => {
                    alert("Error! Kolla i konsolen för mer information.")
                    console.error(err)
                }
            )
    }
)

const customDataContainer = document.querySelector("#custom-data")
const addCustomDataButton = document.querySelector("#add-custom-data")
addCustomDataButton?.addEventListener(
    "click",
    event => {
        event.preventDefault()

        const fieldName = prompt("Vad ska fältet heta? (OBS! Använd inte punker i namnet!)")

        let fieldType
        while (fieldType !== "number" && fieldType !== "text") {
            fieldType = prompt("Vilken typ av fält ska det vara? (Måste vara 'number' eller 'text'!)")
        }

        const label = `<label for="customData.${fieldName}">${fieldName}</label>`
        const input = `<input type="${fieldType}" name="customData.${fieldName}" id="customData.${fieldName}">`

        customDataContainer.innerHTML += label
        customDataContainer.innerHTML += input
    }
)
