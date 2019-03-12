describe('Coach End User Agreement', () => {
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
        cy.visit('/coach-end-user-agreement/?certification=1')
    })

    it(`displays 'Coach End User Agreement'`, () => {
        testText('.first > h3', 'Coach End User Agreement', 'H3')
    })

    // depends on the logged in user
    it(`has 'In Training' sign next to the gears icon`, () => {
        testText('.user-name > p', 'In Training', 'P')
    })

    it(`redirects to dashboard upon clicking on gears icon`, () => {
        cy.get('.user-name > p > a > .dashicons')
            .click()
        cy.url()
            .should('eq', `${Cypress.config().baseUrl}/dashboard/`)
    })

    it(`has 'Select file to upload:' label`, () => {
        testText('#agreement-upload-form > div', 'Select file to upload:', 'DIV')
    })

    it(`has an input for file upload`, () => {
        cy.get('#agreement-upload-form > div input[type="file"]')
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

    it('fails to upload since no file was selected', () => {
        cy.get('div > input[type=file]')
            .click()
        
        cy.get('[type="submit"]')
            .click()
        
        cy.getByText(/^Some information is missing*/i)
            .should('be.visible')
    })   
})
