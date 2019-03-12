describe('Upload Coaching Session', () => {
    function login() {
        cy.fixture('users/admin-cit')
            .then((admin) => {
                cy.visit('/login/')
                cy.get('#user_login').type(admin.email)
                cy.get('#user_pass').type(admin.password)
                cy.get('#wp-submit').click()
            })
    }

    function selectUseLink() {
        return cy.get('#media_upload')
                    .select('Use a Link')
    }

    function selectUploadFile() {
        return cy.get('#media_upload')
                    .select('Upload File')
    }

    const fileUrl = 'https://www.w3.org/TR/PNG/iso_8859-1.txt'

    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/upload-coaching-session/?certification=1')
    })

    it(`displays 'Upload Coaching Session'`, () => {
        cy.get('.first > h3')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Upload Coaching Session')
            })
    })

    // depends on the logged in user
    it(`has 'In Training' sign`, () => {
        cy.get('.user-name > p')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('In Training')
            })
    })

    it(`redirects to dashboard upon clicking on gears icon`, () => {
        cy.get('.user-name > p > a > .dashicons')
            .click()
        cy.url()
            .should('eq', `${Cypress.config().baseUrl}/dashboard/`)
    })

    it(`allows selecting 'Use a Link' item`, () => {
        selectUseLink()
            .should('have.value', '0')
    })

    it(`allows selecting 'Upload File' item`, () => {
        selectUploadFile()
            .should('have.value', '1')
    })

    it(`accepts a link into input field when using a link to existing file`, () => {
        selectUseLink()
        cy.get('#use-link > input')
            .as('linkURL')
            .type(fileUrl)

        cy.get('@linkURL')
            .should('have.value', fileUrl)
    })

    it(`successfully submits a form when using a link to existing file`, () => {
        selectUseLink()
        cy.get('#use-link > input')
            .type(fileUrl)

        cy.get('[type="submit"]')
            .click()
        
        cy.get('.success-message').should('have.text', 'Your coaching session has been saved.')
    })

    it(`displays an error message on submit an empty input field for a link to existing file`, () => {
        selectUseLink()
        cy.get('[type="submit"]')
            .click()

        cy.get('#link-error').should('have.text', 'This field is required.')
    })

    it(`displays an error message on submit an invalid URL to existing file`, () => {
        selectUseLink()
        cy.get('#use-link > input')
            .type('aaaaa')
        cy.get('[type="submit"]')
            .click()

        cy.get('#link-error').should('have.text', 'Please enter a valid URL.')
    })

    it(`displays an error message when submitting a form without selecting a file to upload`, () => {
        selectUploadFile()
        cy.get('#upload-file > input')
            .click()

        cy.get('[type="submit"]')
            .click()

        cy.get('.error-message').should('have.text', 'Some information is missing, please make sure your form is complete.')
    })

})