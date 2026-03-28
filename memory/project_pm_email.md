---
name: HMDG Project Manager Email
description: PM email address for Site Planner approval emails
type: project
---

Project Manager email: felmerald@hmdg.co.uk

**Why:** Used in Phase 1 Site Planner — when client approves their site plan, the structured plan is emailed to the PM at this address, along with a CC to the client.

**How to apply:** Always hardcode or use this as the default "To" address in HMDG_Mailer::send_to_pm(). Never change without user confirmation.
