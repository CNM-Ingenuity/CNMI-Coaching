describe('Take Attendance', () => {
    function login() {
        cy.fixture('users/admin-cct')
            .then((admin) => {
                cy.visit('/login/')
                cy.get('#user_login').type(admin.email)
                cy.get('#user_pass').type(admin.password)
                cy.get('#wp-submit').click()
            })
    }

    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/student-progress/?progress=1')
    })

    it(`displays 'In Training' title`, () => {
        cy.get('.first > h3')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('In Training')
            })
    })

    it(`has 'Certified Trainer' sign`, () => {
        cy.get('.user-name > p')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Certified Trainer')
            })
    })

    it(`redirects to dashboard upon clickin on gears icon`, () => {
        cy.get('.user-name > p > a > .dashicons')
            .click()
        cy.url()
            .should('eq', `${Cypress.config().baseUrl}/dashboard/`)
    })

    it(`has a table with four specific rows`, () => {
        cy.get('table:first')
            .as('firstTable')
            .get('tr:first > td:first')
            .should('have.text', 'Sessions Attended')
        
        cy.get('@firstTable')
            .get('tr:nth-of-type(2) > td:first')
            .should('have.text', 'Training Complete')
        
        cy.get('@firstTable')
            .get('tr:nth-of-type(3) > td:first')
            .should('have.text', 'Fieldwork')
        
        cy.get('@firstTable')
            .get('tr:nth-of-type(4) > td:first')
            .should('have.text', 'Assessment Complete')
    })

    it(`displays 'Complete' for 'Training Complete'`, () => {
        cy.get(':nth-child(2) > :nth-child(2) > .success-message')
            .should('have.text', 'Complete')
            .should('be.visible')
            .and('have.css', 'background')
    })

    it(`has table 'Coaching Hours' with four specific columns`, () => {
        cy.get('.entry-content > :nth-child(4)')
            .should('have.text', 'Coaching Hours')
        cy.get('.entry-content > table:nth-of-type(2) > tbody > :nth-child(1)')       
            .as('secondTableFirstRow') 
            .find('th:nth-of-type(1)')
            .should('have.text', 'Client Name')

        cy.get('@secondTableFirstRow')
            .find('th:nth-of-type(2)')
            .should('have.text', 'Date')

        cy.get('@secondTableFirstRow')
            .find('th:nth-of-type(3)')
            .should('have.text', 'Minutes')

        cy.get('@secondTableFirstRow')
            .find('th:nth-of-type(4)')
            .should('have.text', 'Comments')
    })

    it(`table 'Coaching Hours' has at least one row and displays number in the third column`, () => {
        cy.get('.entry-content > table:nth-of-type(2) > tbody')
            .as('table')
            .find('tr')
            .its('length')
            .should('be.gt', 2)
        
        cy.get('@table').get(`tr:nth-of-type(2) > :nth-child(3)`)
            .invoke('text')
            .then((text) => {
                assert.typeOf(parseInt(text), 'number', `${text} is number`)
            })
    })

    it(`has string 'Total Training Time:' on the page`, () => {
        cy.getByText(/^Total Training Time:*/)
        .should('be.visible')
    })

    it(`has table that displays 'Coaching Hours'`, () => {
        cy.getByText(/^Coaching Hours$/)
            .should('be.visible')
    })

    it(`has visible 'Coaching Sessions' table name on the page`, () => {
        cy.get('.entry-content > h5:nth-child(8)')
            .should('have.text', 'Coaching Sessions')
    })

    it(`has table of 'Coaching Sessions' with 'Status' and 'Actions' columns`, () => {
        cy.get('.entry-content > table:nth-of-type(4) > tbody > :nth-child(1)')
            .as('fourthTableFirstRow')
            .find('th:nth-of-type(2)')
            .should('have.text', 'Status')

        cy.get('@fourthTableFirstRow')
            .find('th:nth-of-type(3)')
            .should('have.text', 'Actions')
    })

    it(`table of 'Coaching Sessions' has an action button in the 'Actions' column that redirects to the appropriate page upon clicking`, () => {
        const sessionNum = 2
        cy.get(`.entry-content > table:nth-of-type(4) > tbody > tr:nth-of-type(${sessionNum + 1}) > :nth-child(3) > a`)
            .click()

        cy.url()
            .should('eq', `${Cypress.config().baseUrl}/review-coaching-session/?session=${sessionNum}`)
    })

    it(`has a table with only one row, 'Coaching Sessions'`, () => {
        cy.get(`.entry-content > table:nth-of-type(5) > tbody > tr`)
            .as('tableRow')
            .its('length')
            .should('eq', 1)
        
        cy.get('@tableRow')
            .find(':first-child')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Coaching Sessions')
            })

    })

    it(`has table titled 'Coaching Letters'`, () => {
        cy.getByText(/^Coaching Letters$/)
            .should('be.visible')
    })

    it(`has table of 'Coaching Letters' with the 'Actions' column`, () => {
        cy.get('.entry-content > table:nth-of-type(6) > tbody > :nth-child(1)')
            .find('th:nth-of-type(2)')
            .should('have.text', 'Actions')
    })

    it(`has at least one action button in the 'Actions' column of 'Coaching Letters' table that has 'href' attribute`, () => {
        const letterNum = 1
        cy.get(`.entry-content > table:nth-of-type(6) > tbody > tr:nth-of-type(${letterNum + 1}) > :nth-child(2) > a`)
            .should('have.class', 'button')
            .and('have.attr', 'href')
    })

    it(`has table titled 'Coaching Agreements'`, () => {
        cy.getByText(/^Coaching Agreements$/)
            .should('be.visible')
    })

    it(`has at least one action button in the 'Actions' column of 'Coaching Agreements' table that has 'href' attribute`, () => {
        const agreementNum = 1
        cy.get(`.entry-content > table:nth-of-type(7) > tbody > tr:nth-of-type(${agreementNum + 1}`)
            .as('firstRow')
            .find(':first-child')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain(`Agreement ${agreementNum}`)
            })
        
        cy.get('@firstRow')
            .find(':nth-child(2) > a')
            .should('have.class', 'button')
            .and('have.attr', 'href')
    })

    it(`has a visible sign 'Certification Complete'`, () => {
        cy.getByText(/^Certification Complete$/)
            .should('be.visible')
    })

    it(`has at least one 'Mark Complete' button`, () => {
        cy.get('.complete-button')
            .should('have.value', 'Mark Complete')
            .its('length')
            .should('be.gt', 0)
    })

})