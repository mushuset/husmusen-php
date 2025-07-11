import checkIfLoggedIn from "./checkIfLoggedIn.js"
import checkSuccess from "./checkSuccess.js"
import { getColorForName } from "./colors.js"
import husmusenOptions from "./husmusenOptions.js"

const output = document.querySelector(".log")

checkIfLoggedIn()
    .then(
        () => fetch(
            `${husmusenOptions.HUSMUSEN_MOUNT_PATH}/api/1.0.0/log/get?reverse=true`,
            {
                method: "GET",
                headers: {
                    "Husmusen-Access-Token": localStorage.getItem("api-token")
                }
            }
        )
    )
    .then(checkSuccess)
    .then(
        log => {
            const logHTML = log
                .map(
                    logEntry => `
                        <p class="timestamp">${logEntry.timestamp}</p>
                        <p class="prefix" style="color: ${getColorForName(logEntry.prefix)}">${logEntry.prefix}</p>
                        <p class="message">${logEntry.message}</p>
                    `
                ).join("")

            output.innerHTML = logHTML
        }
    )
    .catch(() => output.innerHTML = "<p>Du verkar inte vara inloggad, eller så är du inte en administratör!")
