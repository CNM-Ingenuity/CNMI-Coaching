describe('Letter of Reference', () => {
    function login() {
        cy.visit('/login/')
        cy.get('#user_login').type('matt+cit@11online.us')
        cy.get('#user_pass').type('pSc3gM0IpbicjGwarXC2NyfP')
        cy.get('#wp-submit').click()
    }

    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/submit-letters-of-reference/?certification=1')
    })

    it('fails to submit a letter of reference since no file was selected', () => {
        cy.get('div > input[type=file]')
            .click()

        cy.get('[type="submit"]')
            .click()
        cy.getByText(/^Some information is missing*/i)
    })
})
