describe('Review Coaching Session', () => {
    function login() {
        cy.fixture('users/admin-cct')
            .then((admin) => {
                cy.visit('/login/')
                cy.get('#user_login').type(admin.email)
                cy.get('#user_pass').type(admin.password)
                cy.get('#wp-submit').click()
            })
    }

    function getForm() {
        return cy.get('#coaching-session-review-form')
    }

    function testLabel(currName, childNum, currText) {
        getForm()
            .find(`:nth-child(${childNum})`)
            .contains(currText)
            .should('be.visible')
            .and('have.attr', 'for')
            .invoke('toString')
            .then((text) => {
                expect(text.trim()).to.contain(`${currName}_vc`)
            })
    }

    function testSelect(currName, childNum) {
        getForm()
            .find(`:nth-child(${childNum + 1})`)
            .should('have.attr', 'name')
            .invoke('toString')
            .then((text) => {
                expect(text.trim()).to.contain(`${currName}_vc`)
            })
    }

    function testSelectOption(childNum, option) {
        getForm()
            .find(`:nth-child(${childNum + 1})`)
            .select(option)
            .should('have.value', option.toLowerCase())
    }

    function testComments(currName, childNum) {
        getForm()
            .find(`:nth-child(${childNum + 2})`)
            .should('contain', 'Comments')
            .and('have.attr', 'for')
            .invoke('toString')
            .then((text) => {
                expect(text.trim()).to.contain(`${currName}_text`)
            })
    }

    function testTextarea(currName, childNum) {
        getForm()
            .find(`:nth-child(${childNum + 3})`)
            .should('have.prop', 'tagName')
            .should('eq', 'TEXTAREA')

        getForm()
            .find(`:nth-child(${childNum + 3})`)
            .should('have.attr', 'name')
            .invoke('toString')
            .then((text) => {
                expect(text.trim()).to.contain(`${currName}_text`)
            })  
    }

    function testDropDownItemAndComments(currName, childNum, currText) {
        testLabel(currName, childNum, currText)
        testSelect(currName, childNum)
        testSelectOption(childNum, 'Strong')
        testSelectOption(childNum, 'Good')
        testSelectOption(childNum, 'Improve')
        testComments(currName, childNum)
        testTextarea(currName, childNum)   
    }

    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/review-coaching-session/?session=2')
    })

    it(`displays 'Review Coaching Session' title`, () => {
        cy.get('.first > h3')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Review Coaching Session')
            })
    })

    // depends on the logged in user
    it(`has 'Certified Trainer' sign`, () => {
        cy.get('.user-name > p')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Certified Trainer')
            })
    })

    it(`redirects to dashboard upon clicking on gears icon`, () => {
        cy.get('.user-name > p > a')
            .as('myLink')
            .should('have.attr', 'href')
            .then((href) => {
                cy.visit(href)
                    .url()
                    .should('eq', `${Cypress.config().baseUrl}${href}/`)
            })
    })

    it(`has 'Download File Here' link`, () => {
        const fileExt = '.txt'
        cy.getByText(/^Download File$/)
            .as('myElement')
            .should('be.visible')
        
        cy.get('@myElement')
            .find('a')
            .should('have.attr', 'href')
            .then((href) => {
                expect(href).to.contain(fileExt)
            })
    })

    it(`has 'Setting the Foundation and Co-Creating the Relationship' title visible`, () => {
        getForm()
            .find(':nth-child(3)')
            .invoke('text')
            .then((text) => {
                expect(text.trim()).to.contain('Setting the Foundation and Co-Creating the Relationship')
            })
    })

    it(`has dropdown list and comments textarea for establishing trust`, () => {
        testDropDownItemAndComments('establish_trust', 4, 'Establishes trust with the person')
    })

    it(`has dropdown list and comments textarea for effective assessments & tools`, () => {
        testDropDownItemAndComments('effective_assessments', 8, 'Uses effective assessments & tools')
    })

    it(`has dropdown select options and comments textarea for 'Respects person's decisions & goals'`, () => {
        testDropDownItemAndComments('respect_decisions', 12, 'Respects person\'s decisions & goals')
    })

    it(`has 'Communicating Effectively' title visible`, () => {
        cy.getByText(/^Communicating Effectively$/)
            .should('be.visible')
    })

    it(`has dropdown select options and comments textarea for 'Listening: Focuses completely on the person'`, () => {
        testDropDownItemAndComments('listen_focus', 17, 'Listening: Focuses completely on the person')
    })

    it(`has dropdown select options and comments textarea for 'Asks powerful open-ended questions'`, () => {
        testDropDownItemAndComments('asks_powerful', 21, 'Asks powerful open-ended questions')
    })

    it(`has dropdown select options and comments textarea for 'Asks questions that motivate commitment & action'`, () => {
        testDropDownItemAndComments('asks_motivate', 25, 'Asks questions that motivate commitment & action')
    })

    it(`has 'Facilitating Learning' title visible`, () => {
        cy.getByText(/^Facilitating Learning$/)
            .should('be.visible')
    })

    it(`has dropdown select options and comments textarea for 'Helps the person to discover for themselves'`, () => {
        testDropDownItemAndComments('helps_discover', 30, 'Helps the person to discover for themselves')
    })

    it(`has dropdown select options and comments textarea for 'Helps the person to focus on desired outcomes'`, () => {
        testDropDownItemAndComments('helps_focus', 34, 'Helps the person to focus on desired outcomes')
    })

    it(`has 'Designing Actions and Managing Progress' title visible`, () => {
        cy.getByText(/^Designing Actions and Managing Progress$/)
            .should('be.visible')
    })

    it(`has dropdown select options and comments textarea for 'Co-creates an action plan with goals'`, () => {
        testDropDownItemAndComments('co_creates_action', 39, 'Co-creates an action plan with goals')
    })

    it(`has dropdown select options and comments textarea for 'Prepares for managing progress & accountability'`, () => {
        testDropDownItemAndComments('prepares_managing_progress', 43, 'Prepares for managing progress & accountability')
    })

    it(`has dropdown select options labeled 'Session Accepted'`, () => {
        getForm()
            .find(`:nth-child(47)`)
            .contains('Session Accepted')
            .should('be.visible')
            .and('have.attr', 'for')
            .invoke('toString')
            .then((text) => {
                expect(text.trim()).to.contain('session_accepted')
            })
        
        getForm()
            .find(`:nth-child(48)`)
            .select('Yes')
            .should('have.value', '1')
        
        getForm()
            .find(`:nth-child(48)`)
            .select('No')
            .should('have.value', '0')
    })

    it(`shows an error below each 'Comments' textareas that is empty upon hitting 'Review Session'`, () => {
        cy.get('textarea')
            .as('comments')
            .clear()
        
        cy.get('[type="submit"]').click()

        cy.get('@comments')
            .then((colection) => { 
                const colLength = colection.length
                for (let i = 0; i < colLength; i++) {
                    cy.get(`#${colection[i].name}-error`)
                        .should('have.text', 'This field is required.')
                        .should('have.class', 'error')
                        .and('be.visible')
                }
            })   
    })

    it(`allows submitting the form if all comments textareas are filled out`, () => {
        const str = 'My comments'
        cy.get('textarea')
            .each(($el) => { 
                $el.text(str)
            })
        
        cy.get('[type="submit"]').click()

        // there are no error messages on the page
        cy.get('.error')
            .should('not.exist')
    })
})