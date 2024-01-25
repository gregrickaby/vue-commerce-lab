import type { Meta, StoryObj } from '@storybook/vue3'

import ApplicationLogo from '@/Components/Atoms/ApplicationLogo.vue'

// More on how to set up stories at: https://storybook.js.org/docs/writing-stories
const meta = {
    title: 'ApplicationLogo',
    component: ApplicationLogo
} satisfies Meta<typeof ApplicationLogo>

export default meta
type Story = StoryObj<typeof meta>

/*
 *👇 Render functions are a framework specific feature to allow you control on how the component renders.
 * See https://storybook.js.org/docs/api/csf
 * to learn how to use render functions.
 */
export const Basic: Story = {
    render: () => ({
        components: { ApplicationLogo },
        template: '<ApplicationLogo />'
    })
}
