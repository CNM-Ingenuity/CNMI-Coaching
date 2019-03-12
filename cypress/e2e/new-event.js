describe('New Event', () => {
    function login() {
        cy.fixture('users/admin-co')
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

    function testLink(selector, text) {
        cy.get(selector)
            .contains(text)
            .should('have.attr', 'href')
            .then((href) => {
                cy.visit(href)
                    .url()
                    .should('eq', href)
            })
    }

    function testSpecialElement(selector, arr, tag, text) {
        cy.get(selector)
            .as('myElement')
            .should('be.visible')
            .should('have.prop', 'tagName')
            .and('eq', tag)

        cy.get('@myElement')

        if (text !== '') {
            cy.contains(text)
        }

        for (let i = 0; i < arr.length; i++) {
            (i === arr.length - 1) ? cy.and('have.attr', arr[i][0], arr[i][1]) : cy.should('have.attr', arr[i][0], arr[i][1])
        }
    }

    before(() => {
        login()
    })

    beforeEach(() => {
        cy.visit('/event/new-event/')
    })

    it(`has 'All Events' link`, () => {
        testLink('.tribe-events-back > a', 'All Events')
    })

    it(`has 'This event has passed.' sign`, () => {
        testText('.tribe-events-notices > ul > li', 'This event has passed.', 'LI')
    })

    it(`has 'New Event' title`, () => {
        testText('#tribe-events-content > .tribe-events-single-event-title', 'New Event', 'H1')
    })

    it(`has event information`, () => {
        testText('#tribe-events-content > .tribe-events-single-event-title', 'New Event', 'H1')
        testText('.tribe-event-date-start', 'February 28 @ 8:00 am', 'SPAN')
        testText('.tribe-event-time', '5:00 pm', 'SPAN')
        testText('.tribe-events-cost', 'Free', 'SPAN') 
    })

    it(`has 'New event' subtitile`, () => {
        testText('.tribe-events-single-event-description.tribe-events-content > p', 'New event', 'P')
    })

    it(`has 'Details' information`, () => {
        testText('h2.tribe-events-single-section-title', 'Details', 'H2')
        testText('dt.tribe-events-start-date-label', 'Date:', 'DT')
        testText(':nth-child(2) > .tribe-events-abbr', 'February 28', 'ABBR')
        testText('dt.tribe-events-start-time-label', 'Time:', 'DT')
        testText(':nth-child(4) > .tribe-events-abbr', '8:00 am - 5:00 pm', 'DIV')
        testText('.tribe-events-event-cost-label', 'Cost:', 'DT')
        testText('.tribe-events-event-cost', 'Free', 'DD')
        testText('dt.tribe-events-event-categories-label', 'Event Category:', 'DT')
    })

    it(`has 'Financial Coach Training' link`, () => {
        testLink('.tribe-events-event-categories > a', 'Financial Coach Training')
    })

    it(`has 'Tickets' titile and message`, () => {
        testText('.tribe-events-tickets-title', 'Tickets', 'H2')
        testText('.tickets-unavailable', 'Tickets are not available as this event has passed.', 'DIV')
    })

    it(`has 'Academic Coach Training Session 2' link`, () => {
        testLink('li.tribe-events-nav-previous > a', 'Academic Coach Training Session 2')
    })

    it(`has a link to 'Google Calendar'`, () => {
        const arr = [['href', 'https://www.google.com/calendar/event?action=TEMPLATE&text=New+Event&dates=20190228T080000/20190228T170000&details=New+event+%0A&location&trp=false&sprop=website:http://cnmi.wpengine.com&ctz=Atlantic%2FAzores'], ['title', 'Add to Google Calendar']]
        testSpecialElement('.tribe-events-gcal', arr, 'A', '+ Google Calendar')
    })

    it(`has a link to '+ iCal Export'`, () => {
        const arr = [['href', 'http://cnmi.wpengine.com/event/new-event/?ical=1&tribe_display='], ['title', 'Download .ics file']]
        testSpecialElement('.tribe-events-ical', arr, 'A', '+ iCal Export')
    }) 
})