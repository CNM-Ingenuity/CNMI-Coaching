describe('Letter of Reference', () => {
    function login() {
        cy.fixture('users/admin-cit')
            .then((admin) => {
                cy.visit('/login/')
                cy.get('#user_login').type(admin.email)
                cy.get('#user_pass').type(admin.password)
                cy.get('#wp-submit').click()
            })
    }

    function testText(selector, textContent, tag) {
        cy.get(selector)
            .contains(textContent)
            .should('be.visible')
            .should('have.prop', 'tagName')
            .and('eq', tag)
    }

    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/submit-letters-of-reference/?certification=1')
    })

    it(`displays 'Submit Letters of Reference'`, () => {
        testText('.first > h3', 'Submit Letters of Reference', 'H3')
    })

    it(`has 'In Training' sign next to the gears icon`, () => {
        testText('.user-name > p', 'In Training', 'P')
    })

    it(`redirects to dashboard upon clickin on gears icon`, () => {
        cy.get('.user-name > p > a > .dashicons')
            .click()
        cy.url()
            .should('eq', `${Cypress.config().baseUrl}/dashboard/`)
    })

    it(`has 'Select file to upload:' label`, () => {
        testText('#letter-upload-form > div', 'Select file to upload:', 'DIV')
    })

    it(`has an input for file upload`, () => {
        cy.get('#letter-upload-form > div input[type="file"]')
            .should('be.visible')
            .should('have.attr', 'name', 'file')
            .and('have.attr', 'type', 'file')
    })

    it(`has a submit button for file upload`, () => {
        cy.get('input[type="submit"]')
            .should('be.visible')
            .should('have.value', 'Upload File')
            .should('have.attr', 'name', 'submit')
            .and('have.attr', 'required')
    })

    it('fails to submit a letter of reference since no file was selected', () => {
        cy.get('div > input[type=file]')
            .click()

        cy.get('[type="submit"]')
            .click()
        
        cy.getByText(/^Some information is missing*/i)
            .should('be.visible')
    })
})
