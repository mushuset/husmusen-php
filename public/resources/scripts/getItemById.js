import husmusenOptions from "./husmusenOptions.js"

const getItemByIdForm = document.querySelector("#get-item-by-id")
getItemByIdForm.addEventListener(
    "submit",
    event => {
        event.preventDefault()
        const formData = new FormData(getItemByIdForm)
        const itemID = formData.get("itemID")
        window.location.assign(`${husmusenOptions.HUSMUSEN_MOUNT_PATH}/app/item/${itemID}`)
    }
)
