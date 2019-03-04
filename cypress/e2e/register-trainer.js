describe('Register Trainer', () => {
    function generateRandomLength() {
        return Math.floor(Math.random() * 10 + 3);
    }

    function generateFakeName(len) {
        const charSet1 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
        const charSet2 = 'abcdefghijklmnopqrstuvwxyz'
        const charLen = charSet1.length
        let currName = ''

        let randomPoz = Math.floor(Math.random() * charLen)
        currName += charSet1.substring(randomPoz, randomPoz + 1)

        for (let i = 1; i < len; i++) {
            randomPoz = Math.floor(Math.random() * charLen)
            currName += charSet2.substring(randomPoz, randomPoz + 1)
        }

        return currName
    }

    function generateFakeEmail(firstName, lastName) {
        return firstName.toLowerCase() + '.' + lastName.toLowerCase() + '@gmail.com'
    }

    function submitNewTrainer(first, last, email) {
        cy.get('input[label="first_name"]').type(first)
        cy.get('input[label="last_name"]').type(last)
        cy.get('input[label="email"]').type(email)
        cy.get('input[type="submit"]').click()
    }

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
        testLabelAndInput('first_name', 1, 'First Name', 'Sarah')
    })

    it(`has label 'Last Name' and corresponding input field`, () => {
        testLabelAndInput('last_name', 3, 'Last Name', 'Martinsen')
    })

    it(`has label 'Email' and corresponding input field`, () => {
        testLabelAndInput('email', 5, 'Email', 'sarah.martinsen@gmail.com')
    })

    it(`shows an error message below each of input fields that is empty upon hitting the 'Register' button`, () => {
        cy.get('input[label]')
            .as('inputs')
            .clear()

        cy.get('[type="submit"]').click()

        cy.get('@inputs')
            .then((colection) => {
                const colLength = colection.length
                for (let i = 0; i < colLength; i++) {
                    cy.get(`#${colection[i].name}-error`)
                        .should('have.class', 'error')
                        .should('have.text', 'This field is required.')
                        .and('be.visible')
                }
            })
    })

    it(`allows submittin the form if all input fields are filled out with a new user data`, () => {
        const currFirstName = generateFakeName(generateRandomLength())
        const currLastName = generateFakeName(generateRandomLength())
        const currEmail = generateFakeEmail(currFirstName, currLastName)

        submitNewTrainer(currFirstName, currLastName, currEmail)

        // there are no error messages on the page
        cy.get('.error')
            .should('not.exist')
        
        // there is a success message
        cy.get('p.success-message')
            .should('be.visible')
            .should('have.text', 'Trainer successfully created.')
            .and('have.css', 'background')
            
        // all input fields were reset (cleared up)
        cy.get('input[label="first_name"]')
            .should('be.empty')
        cy.get('input[label="last_name"]')
            .should('be.empty')
        cy.get('input[label="email"]')
            .should('be.empty')     
    })

    it(`shows an error while trying to register an existing trainer`, () => {
        const currFirstName = generateFakeName(generateRandomLength())
        const currLastName = generateFakeName(generateRandomLength())
        const currEmail = generateFakeEmail(currFirstName, currLastName)

        submitNewTrainer(currFirstName, currLastName, currEmail)
        submitNewTrainer(currFirstName, currLastName, currEmail)

        cy.get('p.error-message')
            .should('have.text', 'This user already has an account.')
            .and('be.visible')
    })
})