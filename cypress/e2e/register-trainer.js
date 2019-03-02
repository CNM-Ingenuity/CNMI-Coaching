describe('Review Coaching Session', () => {
    const firstName = 'Ryan'
    const lastName = 'Smith'
    const email = 'ryan.smith@company.com'

    function login() {
        cy.fixture('users/admin-licorg')
            .then((admin) => {
                cy.visit('/login/')
                cy.get('#user_login').type(admin.email)
                cy.get('#user_pass').type(admin.password)
                cy.get('#wp-submit').click()
            })
    }

    function getForm() {
        return cy.get('#register-trainer')
    }

    function testLabel(currName, childNum, currText) {
        getForm()
            .find(`:nth-child(${childNum})`)
            .contains(currText)
            .should('be.visible')
            .and('have.attr', 'for')
            .invoke('toString')
            .then((text) => {
                expect(text.trim()).to.contain(currName)
            })
    }
    
    function testInput(currName, childNum, currInput) { 
        getForm()
            .find(`:nth-child(${childNum + 1})`)
            .type(currInput)
            .should('have.value', currInput)
            .and('have.attr', 'name')
            .invoke('toString')
            .then((text) => {
                expect(text.trim()).to.contain(currName)
            })
    }

    function testLabelAndInput(currName, childNum, currText, currInput) {
        testLabel(currName, childNum, currText)
        testInput(currName, childNum, currInput)
    }

    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/register-trainer/')
    })

    it(`displays 'Register Trainer' title`, () => {
        cy.get('.first > h3')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Register Trainer')
            })
    })

    it(`says 'Matt Harris' next to the gears icon`, () => {
        cy.get('.user-name > p')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Matt Harris')
            })
    })

    it(`redirects to dashboard upon clickin on gears icon`, () => {
        cy.get('.user-name > p > a')
            .as('myLink')
            .should('have.attr', 'href')
            .then((href) => {
                cy.visit(href)
                    .url()
                    .should('eq', `${Cypress.config().baseUrl}${href}/`)
            })
    })

    it(`has label 'First Name' and corresponding input field`, () => {   
        testLabelAndInput('first_name', 1, 'First Name', firstName)
    })

    it(`has label 'Last Name' and corresponding input field`, () => {
        testLabelAndInput('last_name', 3, 'Last Name', lastName)
    })

    it.only(`has label 'Email' and corresponding input field`, () => {
        testLabelAndInput('email', 5, 'Email', email)
    })


})