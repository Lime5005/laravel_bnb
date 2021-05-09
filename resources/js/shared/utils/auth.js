export function isLoggedIn() {
    return localStorage.getItem("IsLoggedIn") == true
}

export function logIn() {
    return localStorage.setItem("isLoggedIn", true)
}

export function logOut() {
    return localStorage.setItem("isLoggedIn", false)
}